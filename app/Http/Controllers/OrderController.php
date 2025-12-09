<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 🛒 Store — Pembeli membuat pesanan
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_id'        => 'required|exists:menus,id',
            'quantity'       => 'required|integer|min:1',
            'customer_name'  => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        // Ambil menu
        $menu = Menu::findOrFail($request->menu_id);

        // ⛔ CEK STOK
        if ($menu->stock < $request->quantity) {
            return redirect()->back()->withErrors([
                'stock' => 'Stok tidak mencukupi! Sisa stok: ' . $menu->stock
            ]);
        }

        // Detail pesanan (disimpan dalam JSON)
        $details = [
            [
                'menu_id'  => $menu->id, // tambahin id biar bisa dipakai buat restock
                'menu'     => $menu->name,
                'price'    => $menu->price,
                'quantity' => (int) $request->quantity,
                'subtotal' => $menu->price * $request->quantity,
            ]
        ];

        // Simpan pesanan ke database
        $order = Order::create([
            'user_id'        => $menu->user_id,
            'menu_id'        => $menu->id,
            'order_number'   => 'ORD-' . strtoupper(Str::random(6)),
            'customer_name'  => $request->customer_name ?? 'Pembeli Umum',
            'customer_phone' => $request->customer_phone ?? '08123456789',
            'total_price'    => $menu->price * $request->quantity,
            'status'         => 'pending',
            'order_date'     => Carbon::today(),
            'details'        => json_encode($details, JSON_UNESCAPED_UNICODE),
            'quantity'       => (int) $request->quantity,
        ]);

        Log::info('Order baru dibuat:', ['order_id' => $order->id]);

        // 🔥 KURANGI STOK
        $menu->stock -= $request->quantity;
        $menu->save();

        return redirect()->route('buyer.success', ['order' => $order->id]);
    }

    /**
     * 🔄 Update status pesanan (penjual)
     * - kalau status diganti jadi "cancelled", stok menu DIKEMBALIKAN
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivering,completed,cancelled',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                // kunci order biar aman dari race-condition
                $order = Order::lockForUpdate()->findOrFail($id);
                $oldStatus = $order->status;
                $newStatus = $request->status;

                // Kalau dari status apa pun → diganti jadi cancelled
                // dan sebelumnya BUKAN cancelled → balikin stok
                if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {

                    // 1️⃣ Coba pakai relasi order_items kalau ada
                    if ($order->items()->exists()) {
                        foreach ($order->items as $item) {
                            if ($item->menu) {
                                $menu = Menu::lockForUpdate()->find($item->menu_id);
                                if ($menu) {
                                    $menu->stock += max((int) $item->quantity, 1);
                                    $menu->save();
                                }
                            }
                        }
                    } else {
                        // 2️⃣ Fallback pakai kolom details (JSON)
                        $details = json_decode($order->details, true);

                        if (is_array($details) && count($details)) {
                            foreach ($details as $detail) {
                                $menuId = $detail['menu_id'] ?? $order->menu_id;
                                $qty    = isset($detail['quantity'])
                                            ? (int) $detail['quantity']
                                            : (int) ($order->quantity ?? 1);

                                if ($menuId) {
                                    $menu = Menu::lockForUpdate()->find($menuId);
                                    if ($menu) {
                                        $menu->stock += max($qty, 1);
                                        $menu->save();
                                    }
                                }
                            }
                        } else {
                            // 3️⃣ Fallback terakhir: pakai menu_id & quantity di tabel orders
                            if ($order->menu_id) {
                                $menu = Menu::lockForUpdate()->find($order->menu_id);
                                if ($menu) {
                                    $qty = (int) ($order->quantity ?? 1);
                                    $menu->stock += max($qty, 1);
                                    $menu->save();
                                }
                            }
                        }
                    }
                }

                // Simpan status baru
                $order->status = $newStatus;
                $order->save();
            });

            return back()->with('success', 'Status pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update status order', [
                'order_id' => $id,
                'error'    => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui status pesanan.');
        }
    }

    /**
     * 📋 Halaman daftar orderan penjual
     */
   public function index(Request $request)
{
    $query = Order::query();

    // 🔍 Search
    if ($request->filled('search')) {
        $keyword = $request->search;

        $query->where(function($q) use ($keyword) {
            $q->where('order_number', 'LIKE', "%$keyword%")
              ->orWhere('customer_name', 'LIKE', "%$keyword%")
              ->orWhere('status', 'LIKE', "%$keyword%");
        });
    }

    // 📅 Filter tanggal (created_at atau order_date?)
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // Urutkan terbaru
    $orders = $query->orderBy('created_at', 'desc')->get();

    return view('seller.orders.index', [
        'orders' => $orders,
        'date'   => $request->date ?? null,
        'search' => $request->search ?? null,
    ]);
}


    /**
     * 🔍 Halaman detail pesanan
     */
    public function show(Order $order)
    {
        $details = json_decode($order->details, true);

        // fallback jika details null / rusak
        if (!$details) {
            $details = [[
                'menu'     => $order->menu->name ?? 'Menu tidak diketahui',
                'quantity' => 1,
                'subtotal' => $order->total_price,
            ]];
        }

        return view('seller.orders.show', compact('order', 'details'));
    }

    /**
     * 🎉 Halaman sukses pembeli
     */
    public function success(Order $order)
    {
        $details = json_decode($order->details, true);
        return view('buyer.success', compact('order', 'details'));
 
    }
}


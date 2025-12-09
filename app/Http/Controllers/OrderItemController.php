<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    /**
     * Tampilkan daftar item pesanan
     */
    public function index()
    {
        $items = OrderItem::with(['order', 'menu'])
            ->whereHas('order', function ($q) {
                $q->where('seller_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('seller.orders.items', compact('items'));
    }

    /**
     * Form tambah item baru ke order
     */
    public function create()
    {
        $orders = Order::where('seller_id', Auth::id())->get();
        $menus = Menu::where('user_id', Auth::id())->get();

        return view('seller.orders.item_create', compact('orders', 'menus'));
    }

    /**
     * Simpan item baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $subtotal = $menu->price * $request->quantity;

        OrderItem::create([
            'order_id' => $request->order_id,
            'menu_id' => $menu->id,
            'quantity' => $request->quantity,
            'price' => $menu->price,
            'subtotal' => $subtotal,
        ]);

        // Update total di order utama
        $order = Order::findOrFail($request->order_id);
        $order->total_price += $subtotal;
        $order->save();

        return redirect()->route('seller.orders.index')
            ->with('success', 'Item berhasil ditambahkan ke pesanan!');
    }

    /**
     * Detail satu item
     */
    public function show(OrderItem $orderItem)
    {
        return view('seller.orders.item_show', compact('orderItem'));
    }

    /**
     * Form edit item
     */
    public function edit(OrderItem $orderItem)
    {
        $menus = Menu::where('user_id', Auth::id())->get();
        return view('seller.orders.item_edit', compact('orderItem', 'menus'));
    }

    /**
     * Update item pesanan
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $oldSubtotal = $orderItem->subtotal;
        $newSubtotal = $menu->price * $request->quantity;

        // Update item
        $orderItem->update([
            'menu_id' => $menu->id,
            'price' => $menu->price,
            'quantity' => $request->quantity,
            'subtotal' => $newSubtotal,
        ]);

        // Update total order
        $order = $orderItem->order;
        $order->total_price = $order->total_price - $oldSubtotal + $newSubtotal;
        $order->save();

        return redirect()->route('seller.orders.index')
            ->with('success', 'Item pesanan berhasil diperbarui!');
    }

    /**
     * Hapus item pesanan
     */
    public function destroy(OrderItem $orderItem)
    {
        $order = $orderItem->order;
        $order->total_price -= $orderItem->subtotal;
        $order->save();

        $orderItem->delete();

        return redirect()->route('seller.orders.index')
            ->with('success', 'Item pesanan berhasil dihapus!');
    }
}

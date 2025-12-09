<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Menu;
use App\Models\Order;
use App\Models\BuyerToken;
use Carbon\Carbon;
use App\Events\OrderCreated;

class BuyerController extends Controller
{

    /* ============================================================
       🔐  Sistem Token Baru: 10 Slot, Reusable, Auto-Expire 1 Jam
       ============================================================*/

    /** 🔍 Cek token aktif / expired */
    private function ensureActiveBuyerToken()
    {
        $tokenId = session('buyer_token_id');

        if (!$tokenId) {
            session()->forget(['buyer_queue_number', 'buyer_token', 'buyer_token_id']);
            return redirect()->route('buyer.qr')
                ->withErrors('Sesi tidak ditemukan. Silakan scan QR lagi.');
        }

        $bt = BuyerToken::find($tokenId);
        if (!$bt || !$bt->is_active) {
            session()->forget(['buyer_queue_number', 'buyer_token', 'buyer_token_id']);
            return redirect()->route('buyer.qr')
                ->withErrors('Sesi Anda sudah tidak aktif.');
        }

        // Auto logout jika idle > 1 jam
        if ($bt->last_activity_at && $bt->last_activity_at->lt(now()->subHour())) {
            $bt->update([
                'is_active' => false,
                'user_id' => null,
                'last_activity_at' => null,
            ]);

            session()->forget(['buyer_queue_number', 'buyer_token', 'buyer_token_id']);

            return redirect()->route('buyer.qr')
                ->withErrors('Sesi berakhir karena tidak ada aktivitas 1 jam.');
        }

        // Update aktivitas
        $bt->update(['last_activity_at' => now()]);

        return $bt;
    }

    /** 📱 Halaman QR */
    public function qrPage()
    {
        BuyerToken::whereNotNull('last_activity_at')
            ->where('last_activity_at', '<', now()->subHour())
            ->update([
                'is_active' => false,
                'user_id' => null,
                'last_activity_at' => null,
            ]);

        $bt = BuyerToken::where('is_active', false)
            ->orderBy('queue_number')
            ->first();

        if (!$bt) {
            return view('buyer.qr', [
                'nextQueue' => null,
                'token' => null,
                'url' => null,
                'error' => 'Semua token sedang dipakai, tunggu sebentar...'
            ]);
        }

        $url = route('buyer.token', ['token' => $bt->token]);

        return view('buyer.qr', [
            'nextQueue' => $bt->queue_number,
            'token' => $bt->token,
            'url' => $url,
        ]);
    }

    /** 🔗 Login Token */
    public function tokenLogin($token)
    {
        $bt = BuyerToken::where('token', $token)->first();

        if (!$bt) {
            return redirect()->route('buyer.qr')
                ->withErrors('Token tidak valid atau sudah dipakai.');
        }

        $bt->update([
            'is_active' => true,
            'last_activity_at' => now(),
        ]);

        session([
            'buyer_queue_number' => $bt->queue_number,
            'buyer_token'        => $token,
            'buyer_token_id'     => $bt->id,
        ]);

        return redirect()->route('buyer.dashboard');
    }

    /* ======================= DASHBOARD ======================== */

   public function dashboard()
{
    $resp = $this->ensureActiveBuyerToken();
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        return $resp;
    }

    $queue_number = session('buyer_queue_number');

    return view('buyer.dashboard', compact('queue_number'));
}

   public function menu()
{
    $resp = $this->ensureActiveBuyerToken();
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        return $resp;
    }

    $menus = Menu::where('is_available', 1)->get();
    $queue_number = session('buyer_queue_number');

    return view('buyer.menus', compact('menus', 'queue_number'));
}


    /* ============================================================
       🧾 CHECKOUT (BUAT ORDER, TAPI BELUM POTONG STOK!)
       ============================================================*/
 public function checkout(Request $request)
{
    $items = $request->items;

if (is_string($items)) {
    $items = json_decode($items, true);
}

    // Validasi token
    $resp = $this->ensureActiveBuyerToken();
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        return $resp;
    }

   // Ambil items dari Axios POST body
$items = $request->input('items');

if (is_string($items)) {
    $items = json_decode($items, true);
}


// Validasi format
if (!$items || !is_array($items)) {
    return response()->json(['error' => 'Format data pesanan tidak valid'], 422);
}


    $details = [];
    $total = 0;

    foreach ($items as $item) {

        if (!isset($item['menu_id']) || !isset($item['quantity'])) {
            return response()->json(['error' => 'Item pesanan tidak lengkap'], 422);
        }

        $menu = Menu::find($item['menu_id']);
        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 422);
        }

        $qty = max((int)$item['quantity'], 1);

        if ($menu->stock < $qty) {
            return response()->json([
                'error' => "Stok {$menu->name} tersisa {$menu->stock}"
            ], 422);
        }

        $subtotal = $menu->price * $qty;
        $total += $subtotal;

        $details[] = [
            'menu_id'  => $menu->id,
            'menu'     => $menu->name,
            'price'    => $menu->price,
            'quantity' => $qty,
            'subtotal' => $subtotal,
        ];
    }

    session([
        'cart_session' => [
            'queue'   => session('buyer_queue_number'),
            'details' => $details,
            'total'   => $total,
        ]
    ]);

    return response()->json([
        'redirect_url' => route('buyer.payment.draft')
    ]);
}

public function paymentDraft()
{
    $resp = $this->ensureActiveBuyerToken();
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        return $resp;
    }

    // Ambil data keranjang dari session
    $cartSession = session('cart_session');

    if (!$cartSession || !isset($cartSession['details'])) {
        return redirect()->route('buyer.menu')
            ->withErrors('Keranjang kosong. Silakan pesan ulang.');
    }

    return view('buyer.payment-draft', [
        'queue_number' => $cartSession['queue'],
        'cart' => $cartSession['details'],
        'total' => $cartSession['total'],
    ]);
}


    /* ======================= PAYMENT PAGE ======================== */

    public function payment(Order $order)
    {
        $details = json_decode($order->details, true) ?? [];

        if (!$details) {
            $menu = Menu::find($order->menu_id);
            if ($menu) {
                $details = [[
                    'menu_id'  => $menu->id,
                    'menu'     => $menu->name,
                    'price'    => $menu->price,
                    'quantity' => 1,
                    'subtotal' => $order->total_price,
                ]];
            }
        }

        $paymentOptions = [
            'transfer' => [
                'label'    => 'Transfer / QRIS Manual',
                'accounts' => [
                    ['bank' => 'BCA', 'no' => '1234567890', 'name' => 'Warung Mas Ndutt94'],
                    ['bank' => 'BRI', 'no' => '0987654321', 'name' => 'Warung Mas Ndutt94'],
                ],
            ],
            'cash' => [
                'label' => 'Bayar Tunai di Kasir',
            ],
        ];

        return view('buyer.payment', compact('order', 'details', 'paymentOptions'));
    }


    /* ======================= SUCCESS PAGE ======================== */

    public function success(Order $order)
    {
        $queueNumber = $order->queue_number ?? session('buyer_last_order_queue') ?? '-';

        $details = json_decode($order->details, true);

        if (!$details) {
            $menu = Menu::find($order->menu_id);
            $details = [[
                'menu_id'  => $menu->id,
                'menu'     => $menu->name,
                'price'    => $menu->price,
                'quantity' => 1,
                'subtotal' => $order->total_price,
            ]];
        }

        return view('buyer.success', compact('order', 'details', 'queueNumber'));
    }

    public function trackOrders()
    {
        $queue_number = session('buyer_queue_number');

        if (!$queue_number) {
            return redirect()->route('buyer.qr')->withErrors('Nomor antrian tidak ditemukan.');
        }

        $orders = Order::where('queue_number', $queue_number)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.orders', compact('orders', 'queue_number'));
    }

    public function getOrderStatus(Order $order)
    {
        return response()->json([
            'status'     => $order->status,
            'updated_at' => $order->updated_at->diffForHumans(),
        ]);
    }

    public function buyerLogout()
    {
        $tokenId = session('buyer_token_id');

        if ($tokenId) {
            BuyerToken::where('id', $tokenId)->update([
                'is_active'       => false,
                'user_id'         => null,
                'last_activity_at'=> null,
            ]);
        }

        session()->forget([
            'buyer_queue_number',
            'buyer_token',
            'buyer_token_id',
            'buyer_last_order_queue',
            'buyer_last_order_id',
        ]);

        return redirect()->route('login.choice')
            ->with('logout_success', 'Anda berhasil keluar.');
    }
    public function submitPayment(Request $request)
{
    $resp = $this->ensureActiveBuyerToken();
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        return $resp;
    }

    $cartSession = session('cart_session');
    if (!$cartSession) {
        return redirect()->route('buyer.menu')->withErrors('Keranjang kosong.');
    }

    // Validasi form
    $validated = $request->validate([
        'customer_name'   => 'required|string|max:100',
        'customer_phone'  => 'required|string|max:20',
        'payment_method'  => 'required|in:transfer,cash',
        'payment_proof'   => 'nullable|image|max:2048',
    ]);

    // Upload bukti pembayaran jika transfer
    $proofPath = null;
    if ($request->payment_method === 'transfer' && $request->hasFile('payment_proof')) {
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
    }

    // Simpan order ke database
   $order = Order::create([
    'order_number'  => 'ORD-' . strtoupper(Str::random(6)),
    'queue_number'  => $cartSession['queue'],
    'details'       => json_encode($cartSession['details']),
    'total_price'   => $cartSession['total'],
    'status'        => 'pending',
    'customer_name' => $validated['customer_name'],
    'customer_phone'=> $validated['customer_phone'],
    'payment_method'=> $validated['payment_method'],
    'payment_proof' => $proofPath,
    'user_id'       => null,
]);

event(new OrderCreated($order));

// 🔥 KURANGI STOK SETIAP MENU YANG DIBELI
foreach ($cartSession['details'] as $item) {
    $menu = Menu::find($item['menu_id']);
    if ($menu) {
        $menu->stock -= (int) $item['quantity'];
        if ($menu->stock < 0) {
            $menu->stock = 0; // keamanan biar ga minus
        }
        $menu->save();
    }
}

    // Hapus keranjang
    session()->forget('cart_session');

    return redirect()->route('buyer.success', ['order' => $order->id]);
}

}


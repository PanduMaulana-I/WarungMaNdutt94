<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * ============================================================
     * 🟦 CREATE PAYMENT (Generate Snap Token)
     * ============================================================
     */
    public function createPayment(Order $order)
    {
        // Load Midtrans config
        Config::$serverKey     = config('midtrans.server_key');
        Config::$clientKey     = config('midtrans.client_key');
        Config::$isProduction  = config('midtrans.is_production');
        Config::$isSanitized   = true;
        Config::$is3ds         = true;

        // ===============================
        // PARAMS UNTUK MIDTRANS SNAP
        // ===============================
        $params = [
            'transaction_details' => [
                'order_id'      => $order->order_number,
                'gross_amount'  => (int) $order->total_price,
            ],

            'enabled_payments' => [
                "gopay", "shopeepay", "bank_transfer",
                "bca_va", "bni_va", "bri_va", "permata_va",
                "echannel", "indomaret"
            ],

            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Pembeli',
                'phone'      => $order->customer_phone ?? '08123',
            ],

            'callbacks' => [
                'finish' => route('buyer.success', ['order' => $order->id]),
            ],

            // WAJIB: URL untuk Midtrans mengirim webhook
            // isi dari .env → MIDTRANS_CALLBACK_URL
            'notification_url' => env('MIDTRANS_CALLBACK_URL'),
        ];

        // CREATE TOKEN
        $snapToken = Snap::getSnapToken($params);

        Log::info('Snap token generated', [
            'order_id' => $order->order_number,
            'token' => $snapToken
        ]);

        // Return token untuk frontend
        return response()->json([
            'token' => $snapToken,
            'client_key' => config('midtrans.client_key'),
        ]);
    }

    /**
     * ============================================================
     * 🟥 MIDTRANS CALLBACK / WEBHOOK
     * ============================================================
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $data = $request->all();

        Log::info("Midtrans Callback RECEIVED", $data);

        // ============================
        // VALIDASI SIGNATURE
        // ============================
        if (!isset($data['signature_key'])) {
            Log::error("Callback invalid, missing signature_key");
            return response()->json(['message' => 'Missing signature'], 403);
        }

        $expectedSignature = hash("sha512",
            $data['order_id'] .
            $data['status_code'] .
            $data['gross_amount'] .
            $serverKey
        );

        if ($expectedSignature !== $data['signature_key']) {
            Log::error("INVALID SIGNATURE", [
                'expected' => $expectedSignature,
                'given' => $data['signature_key'],
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ============================
        // AMBIL ORDER
        // ============================
        $order = Order::where('order_number', $data['order_id'])->first();

        if (!$order) {
            Log::error("ORDER NOT FOUND", ['order_id' => $data['order_id']]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // ============================
        // UPDATE STATUS ORDER
        // ============================
        $status = $data['transaction_status'];

        switch ($status) {
            case "capture":
            case "settlement":
                $order->update(['status' => 'processing']);
                break;

            case "pending":
                $order->update(['status' => 'pending']);
                break;

            case "cancel":
            case "deny":
                $order->update(['status' => 'cancelled']);
                break;

            case "expire":
                $order->update(['status' => 'expired']);
                break;
        }

        Log::info("Order updated after callback", [
            'order_id' => $order->order_number,
            'new_status' => $order->status
        ]);

        return response()->json(['message' => 'Callback processed'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // ===== Verifikasi Signature Key =====
        $expectedSignature = hash('sha512', 
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($expectedSignature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ===== Temukan order berdasarkan order_id =====
        $order = Order::where('order_number', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // ===== Update status berdasarkan callback =====
        $trxStatus = $request->transaction_status;

        if ($trxStatus === 'capture' || $trxStatus === 'settlement') {
            $order->status = 'completed';
        }
        elseif ($trxStatus === 'pending') {
            $order->status = 'pending';
        }
        elseif (in_array($trxStatus, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Callback processed',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Received:', $request->all());

        $orderId = $request->order_id;
        $status  = $request->transaction_status;

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === 'settlement') {
            $order->status = 'completed';
        } elseif ($status === 'pending') {
            $order->status = 'pending';
        } elseif ($status === 'expire') {
            $order->status = 'cancelled';
        }

        $order->save();

        return response()->json(['message' => 'OK'], 200);
    }
    
}

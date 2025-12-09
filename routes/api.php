<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| File ini TIDAK memakai session & TIDAK memakai CSRF,
| jadi cocok untuk:
| - Midtrans Callback
| - Webhook
| - Mobile API
|
| Semua route API otomatis memakai prefix "/api".
|--------------------------------------------------------------------------
*/

// 🔥 Test API aktif
Route::get('/ping', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API aktif 🚀',
        'time' => now()->toDateTimeString()
    ]);
});

// 🔔 MIDTRANS CALLBACK (Wajib untuk Webhook)
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::get('/orders/latest', function () {
    return Order::orderByDesc('created_at')->get();
});
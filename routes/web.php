<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\MidtransController;

/*
|--------------------------------------------------------------------------
| 🌐 HALAMAN UTAMA
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('auth.role-choice'))->name('login.choice');

/*
|--------------------------------------------------------------------------
| 👨‍🍳 LOGIN PENJUAL
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('seller.login');
    Route::post('/login', [AuthController::class, 'login'])->name('seller.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('seller.logout');
});

/*
|--------------------------------------------------------------------------
| 📊 DASHBOARD PENJUAL
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->middleware(['web', 'auth', 'is_seller'])->group(function () {

    Route::get('/dashboard', [SellerDashboardController::class, 'index'])
        ->name('seller.dashboard');

    /*
    |----------------------- MENU CRUD PENJUAL -----------------------
    */
    Route::prefix('menus')->name('seller.menus.')->group(function () {
        Route::get('/', [MenuController::class, 'indexSeller'])->name('index');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------- ORDER PENJUAL -----------------------
    */
    Route::prefix('orders')->name('seller.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

    /*
    |----------------------- LAPORAN -----------------------
    */
    Route::prefix('reports')->name('seller.reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/data', [ReportApiController::class, 'salesData'])->name('data');
        Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
    });

});

/*
|--------------------------------------------------------------------------
| 🧍 ALUR PEMBELI (TOKEN SYSTEM)
|--------------------------------------------------------------------------
*/
Route::prefix('buyer')->name('buyer.')->group(function () {

    // Token-based login
    Route::get('/qr', [BuyerController::class, 'qrPage'])->name('qr');
    Route::get('/token/{token}', [BuyerController::class, 'tokenLogin'])->name('token');

    // Main pages
    Route::get('/dashboard', [BuyerController::class, 'dashboard'])->name('dashboard');
    Route::get('/menu', [BuyerController::class, 'menu'])->name('menu');

    // Checkout → simpan session cart
    Route::post('/checkout', [BuyerController::class, 'checkout'])->name('checkout');

    /*
    |--------------------------------------------------------------------------
    | 🧾 PAYMENT DRAFT (HALAMAN SETELAH CHECKOUT)
    |--------------------------------------------------------------------------
    */
    Route::get('/payment/draft', [BuyerController::class, 'paymentDraft'])
        ->name('payment.draft');

    /*
    |--------------------------------------------------------------------------
    | 📤 SUBMIT PEMBAYARAN (FIX ERROR!!!!)
    |--------------------------------------------------------------------------
    */
    Route::post('/payment/submit', [BuyerController::class, 'submitPayment'])
        ->name('payment.submit');

    /*
    |--------------------------------------------------------------------------
    | 💳 PAYMENT & SUCCESS
    |--------------------------------------------------------------------------
    */
    Route::get('/payment/{order}', [BuyerController::class, 'payment'])->name('payment');
    Route::get('/success/{order}', [BuyerController::class, 'success'])->name('success');

    /*
    |--------------------------------------------------------------------------
    | 📦 ORDER LIST
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', [BuyerController::class, 'trackOrders'])->name('orders');

    /*
    |--------------------------------------------------------------------------
    | 🚪 LOGOUT PEMBELI
    |--------------------------------------------------------------------------
    */
    Route::get('/logout', [BuyerController::class, 'buyerLogout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | 🏪 OUTLET INFO
    |--------------------------------------------------------------------------
    */
    Route::get('/outlet-info', fn() => view('buyer.outlet-info'))->name('outlet.info');
});

/*
|--------------------------------------------------------------------------
| 📡 API ORDER STATUS
|--------------------------------------------------------------------------
*/
Route::get('/buyer/orders/status/{order}', [BuyerController::class, 'getOrderStatus'])
    ->name('buyer.order.status');

/*
|--------------------------------------------------------------------------
| 💳 MIDTRANS
|--------------------------------------------------------------------------
*/
Route::get('/buyer/midtrans/{order}', [MidtransController::class, 'createPayment'])
    ->name('buyer.midtrans');

Route::post('/buyer/midtrans/pay/{order}', [BuyerController::class, 'midtransPay'])
    ->name('buyer.midtrans.pay');

/*
|--------------------------------------------------------------------------
| ⚠️ FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => redirect()->route('login.choice'));

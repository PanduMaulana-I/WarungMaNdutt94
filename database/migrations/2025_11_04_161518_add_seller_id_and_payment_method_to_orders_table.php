<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom seller_id dan payment_method ke tabel orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom seller_id untuk relasi ke penjual (nullable biar aman)
            $table->unsignedBigInteger('seller_id')->nullable()->after('user_id');

            // Kolom payment_method untuk jenis pembayaran (cash, qris, transfer)
            $table->string('payment_method')->nullable()->after('total_price');

            // Kalau mau tambahkan foreign key (opsional, amanin dulu aja)
            // $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Rollback perubahan
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['seller_id', 'payment_method']);
        });
    }
};

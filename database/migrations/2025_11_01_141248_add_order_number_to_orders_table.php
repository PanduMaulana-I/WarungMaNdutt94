<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cegah error SQLite & CI: jangan tambah kolom kalau sudah ada
        if (!Schema::hasColumn('orders', 'order_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_number')
                    ->nullable()
                    ->after('id');
            });
        }
    }

    public function down(): void
    {
        // Hapus kolom hanya jika exist (aman untuk CI & SQLite)
        if (Schema::hasColumn('orders', 'order_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_number');
            });
        }
    }
};

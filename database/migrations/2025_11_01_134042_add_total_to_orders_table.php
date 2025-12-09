<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Tambah kolom total ke tabel orders
            // tipe decimal cocok untuk angka uang (10 digit total, 2 digit di belakang koma)
            $table->decimal('total', 10, 2)->default(0)->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Hapus kolom total kalau rollback
            $table->dropColumn('total');
        });
    }
};

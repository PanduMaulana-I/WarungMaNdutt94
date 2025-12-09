<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('buyer_tokens', function (Blueprint $table) {
        // kolom yang menandakan token sedang dipakai atau tidak
        if (!Schema::hasColumn('buyer_tokens', 'is_active')) {
    $table->boolean('is_active')->default(false)->after('token');
}

        // opsional: kalau kamu punya tabel users untuk pembeli
       if (!Schema::hasColumn('buyer_tokens', 'user_id')) {
    $table->unsignedBigInteger('user_id')->nullable()->after('is_active');
}

if (!Schema::hasColumn('buyer_tokens', 'last_activity_at')) {
    $table->timestamp('last_activity_at')->nullable()->after('user_id');
}

    });
}

public function down()
{
    Schema::table('buyer_tokens', function (Blueprint $table) {
        $table->dropColumn(['is_active', 'user_id', 'last_activity_at']);
    });
}

};

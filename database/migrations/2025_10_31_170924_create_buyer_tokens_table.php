<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('buyer_tokens', function (Blueprint $table) {
    $table->id();
    $table->integer('queue_number')->unique(); // 1..10
    $table->string('token', 64)->nullable();   // token login
    $table->boolean('is_active')->default(false); // dipakai / tidak
    $table->unsignedBigInteger('user_id')->nullable(); // kalau mau di-link ke user
    $table->timestamp('last_activity_at')->nullable(); // buat auto logout
    $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_tokens');
    }
};

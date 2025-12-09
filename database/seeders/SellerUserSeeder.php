<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SellerUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Penjual',
            'email' => 'seller@webtransaksi.test',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan topping ayam dan telur.',
                'price' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Ayam Bakso',
                'description' => 'Mie ayam dengan tambahan bakso sapi.',
                'price' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Minuman segar teh manis dingin.',
                'price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Kopi kekinian dengan campuran susu dan gula aren.',
                'price' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

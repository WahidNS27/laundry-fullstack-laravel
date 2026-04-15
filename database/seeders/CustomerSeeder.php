<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $customers = [
            [
                'customer_name' => 'Agus Budiman',
                'phone' => '081234567890',
                'address' => 'Jl. Kebon Jeruk No 12, Jakarta',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_name' => 'Siti Aisyah',
                'phone' => '081987654321',
                'address' => 'Jl. Melati No 5, Bandung',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('customer')->insert($customers);
    }
}

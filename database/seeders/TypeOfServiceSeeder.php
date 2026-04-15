<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TypeOfServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $services = [
            [
                'service_name' => 'Cuci dan Gosok',
                'price' => 5000,
                'description' => 'Harga per kg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Hanya Cuci',
                'price' => 4500,
                'description' => 'Harga per kg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Hanya Gosok',
                'price' => 5000,
                'description' => 'Harga per kg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_name' => 'Laundry Besar',
                'price' => 7000,
                'description' => 'Selimut, karpet, mantel (Harga per kg)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('type_of_service')->insert($services);
    }
}

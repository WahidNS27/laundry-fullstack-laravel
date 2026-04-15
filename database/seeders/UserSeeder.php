<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $users = [
            [
                'id_level' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@laundry.test',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_level' => 2,
                'name' => 'Operator 1',
                'email' => 'operator@laundry.test',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_level' => 3,
                'name' => 'Pimpinan',
                'email' => 'pimpinan@laundry.test',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('user')->insert($users);
    }
}

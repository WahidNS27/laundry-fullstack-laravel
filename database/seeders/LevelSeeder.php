<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $levels = [
            ['id' => 1, 'level_name' => 'Administrator', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'level_name' => 'Operator', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'level_name' => 'Pimpinan', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('level')->insert($levels);
    }
}

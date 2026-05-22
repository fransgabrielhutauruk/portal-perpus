<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PustakawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            [
                'nama' => 'NURSALIM, S.IP',
                'email' => 'salim@pcr.ac.id',
                'foto' => 'b67d50e3-9109-4381-aac4-6a62aeb70d3d.jpg',
            ],
            [
                'nama' => 'SABINGU, S.IP',
                'email' => 'bing@pcr.ac.id',
                'foto' => '932fa3b4-e355-45bc-825f-5c4406c78043.jpg',
            ],
        ];

        foreach ($items as $item) {
            DB::table('pustakawan')->insert([
                'nama' => $item['nama'],
                'email' => $item['email'],
                'foto' => $item['foto'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periode;
use Carbon\Carbon;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodes = [
            [
                'nama_periode' => 'Periode Usulan Buku Semester Genap 2025/2026',
                'jenis_periode' => 'req_buku',
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_selesai' => Carbon::now()->endOfMonth(),
            ],
            [
                'nama_periode' => 'Periode Kebutuhan Modul Semester Genap 2025/2026',
                'jenis_periode' => 'req_modul',
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_selesai' => Carbon::now()->endOfMonth(),
            ],
            [
                'nama_periode' => 'Periode Bebas Pustaka Wisuda Februari 2026',
                'jenis_periode' => 'req_bebas_pustaka',
                'tanggal_mulai' => Carbon::now()->startOfMonth(),
                'tanggal_selesai' => Carbon::now()->addMonths(2)->endOfMonth(),
            ],
        ];

        foreach ($periodes as $periode) {
            Periode::create($periode);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            [
                'nama_prodi' => 'Teknik Informatika',
                'alias_prodi' => 'TI',
                'alias_jurusan' => 'JTI',
            ],
            [
                'nama_prodi' => 'Sistem Informasi',
                'alias_prodi' => 'SI',
                'alias_jurusan' => 'JTI',
            ],
            [
                'nama_prodi' => 'Teknologi Rekayasa Komputer',
                'alias_prodi' => 'TRK',
                'alias_jurusan' => 'JTI',
            ],
            [
                'nama_prodi' => 'Magister Terapan Teknik Komputer',
                'alias_prodi' => 'MTTK',
                'alias_jurusan' => 'JTI',
            ],
            [
                'nama_prodi' => 'Teknologi Rekayasa Jaringan Telekomunikasi',
                'alias_prodi' => 'TRJT',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Teknik Listrik',
                'alias_prodi' => 'TL',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Teknik Elektronika',
                'alias_prodi' => 'TET',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Teknologi Rekayasa Sistem Elektronika',
                'alias_prodi' => 'TRSE',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Teknologi Rekayasa Mekatronika',
                'alias_prodi' => 'TRM',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Teknik Mesin',
                'alias_prodi' => 'TMS',
                'alias_jurusan' => 'JTIN',
            ],
            [
                'nama_prodi' => 'Hubungan Masyarakat dan Komunikasi Digital',
                'alias_prodi' => 'HMKD',
                'alias_jurusan' => 'JBK',
            ],
            [
                'nama_prodi' => 'Akuntansi Perpajakan',
                'alias_prodi' => 'AKTP',
                'alias_jurusan' => 'JBK',
            ],
            [
                'nama_prodi' => 'Bisnis Digital',
                'alias_prodi' => 'BD',
                'alias_jurusan' => 'JTIN',
            ],
        ];

        foreach ($prodis as $prodi) {
            DB::table('dm_prodi')->insert(array_merge($prodi, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\View\Prodi;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DmProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $contents = [];
        // $get_prodi = Prodi::whereIn('jenjang_pendidikan', ['D4', 'S2'])->get();

        // foreach ($get_prodi as $row) {
        //     $contents[] = [
        //         'alias_prodi' => $row->alias,
        //         'alias_jurusan' => ($row->jurusan_id == 1 ? 'JTI' : ($row->jurusan_id == 2 ? 'JTIN' : ($row->jurusan_id == 3 ? 'JBK' : NULL))),
        //         'nama_prodi' => $row->nama_prodi,
        //         'sync_log' => json_encode([
        //             'synced_by' => 'DEV',
        //             'synced_at' => now(),
        //         ]),
        //         'created_by' => 'DZB',
        //         'updated_by' => null,
        //         'deleted_by' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //         'deleted_at' => null,
        //     ];
        // }

                $contents = [
            [
                'alias_prodi'   => 'TI',
                'alias_jurusan' => 'JTI',
                'nama_prodi'    => 'Teknik Informatika',
                'sync_log'      => json_encode([
                    'synced_by' => 'DEV',
                    'synced_at' => now(),
                ]),
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'alias_prodi'   => 'MI',
                'alias_jurusan' => 'JTIN',
                'nama_prodi'    => 'Manajemen Informatika',
                'sync_log'      => json_encode([
                    'synced_by' => 'DEV',
                    'synced_at' => now(),
                ]),
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ];

        DB::table('dm_prodi')->insert($contents);
    }
}

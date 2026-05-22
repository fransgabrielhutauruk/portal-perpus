<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AksesKoleksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            [
                'nama_akses_koleksi' => 'OPAC',
                'deskripsi' => 'Katalog online untuk mencari dan menelusuri koleksi buku, jurnal, dan bahan pustaka lainnya.',
                'url' => 'https://opac.lib.pcr.ac.id/',
            ],
            [
                'nama_akses_koleksi' => 'Repository',
                'deskripsi' => 'Repositori institusi untuk mengakses karya ilmiah mahasiswa PCR.',
                'url' => 'https://repository.lib.pcr.ac.id/',
            ],
            [
                'nama_akses_koleksi' => 'ISBN Penerbit PCR',
                'deskripsi' => 'Layanan penerbitan dan pengelolaan ISBN untuk karya ilmiah yang diterbitkan PCR.',
                'url' => 'https://isbn.lib.pcr.ac.id',
            ],
            [
                'nama_akses_koleksi' => 'E-Journal PCR',
                'deskripsi' => 'Portal jurnal elektronik yang diterbitkan oleh Politeknik Caltex Riau.',
                'url' => 'https://jurnal.pcr.ac.id',
            ],
            [
                'nama_akses_koleksi' => 'Jurnal Tercetak',
                'deskripsi' => 'Koleksi jurnal tercetak yang tersedia di perpustakaan PCR.',
                'url' => 'https://opac.lib.pcr.ac.id/index.php?keywords=jurnal&search=search',
            ],
            [
                'nama_akses_koleksi' => 'elibPCR',
                'deskripsi' => 'Aplikasi mobile perpustakaan digital resmi PCR (E-book)'
                    . ' untuk mendukung transformasi layanan perpustakaan berbasis teknologi.',
                'url' => 'https://smartlib.gramedia.com/download/pcr',
            ],
        ];

        foreach ($items as $index => $item) {
            DB::table('akses_koleksi')->insert([
                'nama_akses_koleksi' => $item['nama_akses_koleksi'],
                'deskripsi' => $item['deskripsi'],
                'url' => $item['url'],
                'urutan' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}

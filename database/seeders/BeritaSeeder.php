<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beritaData = [
            [
                'judul_berita' => 'Perpustakaan PCR Luncurkan Layanan E-Book Berlangganan',
                'isi_berita' => '<p>Perpustakaan Politeknik Caltex Riau dengan bangga mengumumkan peluncuran layanan e-book berlangganan yang dapat diakses oleh seluruh sivitas akademika. Koleksi e-book ini mencakup lebih dari 5.000 judul dari berbagai penerbit internasional ternama.</p>
                <p>Dengan adanya layanan ini, mahasiswa dan dosen dapat mengakses berbagai referensi akademik kapan saja dan dimana saja melalui platform digital. Koleksi mencakup bidang teknik, manajemen, bisnis, dan ilmu komputer.</p>
                <p>"Kami berharap dengan adanya layanan e-book ini dapat meningkatkan kualitas pembelajaran dan penelitian di lingkungan PCR," ujar Kepala Perpustakaan PCR.</p>
                <p>Untuk mengakses layanan ini, sivitas akademika dapat login menggunakan akun PCR mereka melalui portal perpustakaan.</p>',
                'tanggal_berita' => Carbon::now()->subDays(2),
                'user_id_author' => 1,
                'status_berita' => 'published',
                'meta_desc_berita' => 'Perpustakaan PCR meluncurkan layanan e-book berlangganan dengan lebih dari 5.000 judul untuk sivitas akademika',
                'meta_keyword_berita' => 'e-book, perpustakaan, PCR, digital, koleksi',
                'slug_berita' => 'perpustakaan-pcr-luncurkan-layanan-ebook-berlangganan',
                'filename_berita' => 'perpus-1.jpg',
                'created_by' => '1',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul_berita' => 'Workshop Literasi Informasi untuk Mahasiswa Baru',
                'isi_berita' => '<p>Perpustakaan PCR mengadakan workshop literasi informasi khusus untuk mahasiswa baru angkatan 2026. Workshop ini bertujuan untuk membekali mahasiswa dengan keterampilan dalam mencari, mengevaluasi, dan menggunakan informasi secara efektif.</p>
                <p>Materi workshop meliputi:</p>
                <ul>
                    <li>Pengenalan sistem katalog perpustakaan (OPAC)</li>
                    <li>Teknik pencarian informasi di database online</li>
                    <li>Cara mengakses repository dan e-journal</li>
                    <li>Etika penggunaan informasi dan plagiarisme</li>
                    <li>Pengenalan referensi manager untuk penulisan karya ilmiah</li>
                </ul>
                <p>Workshop akan dilaksanakan selama 2 hari pada tanggal 25-26 Januari 2026 di Ruang Multimedia Perpustakaan. Pendaftaran dapat dilakukan melalui portal perpustakaan.</p>
                <p>"Literasi informasi adalah keterampilan penting di era digital. Kami ingin memastikan mahasiswa memiliki fondasi yang kuat dalam mencari dan menggunakan informasi," kata koordinator workshop.</p>',
                'tanggal_berita' => Carbon::now()->subDays(5),
                'user_id_author' => 1,
                'status_berita' => 'published',
                'meta_desc_berita' => 'Workshop literasi informasi untuk mahasiswa baru PCR dilaksanakan 25-26 Januari 2026 di Perpustakaan PCR',
                'meta_keyword_berita' => 'workshop, literasi informasi, mahasiswa baru, perpustakaan PCR',
                'slug_berita' => 'workshop-literasi-informasi-untuk-mahasiswa-baru',
                'filename_berita' => 'perpus-2.jpg',
                'created_by' => '1',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'judul_berita' => 'Penambahan Koleksi Buku Terbaru di Perpustakaan PCR',
                'isi_berita' => '<p>Perpustakaan Politeknik Caltex Riau telah menambahkan 250 judul buku terbaru ke dalam koleksinya pada bulan Januari 2026. Penambahan koleksi ini merupakan hasil dari usulan koleksi yang diajukan oleh dosen dan mahasiswa pada semester sebelumnya.</p>
                <p>Koleksi baru mencakup berbagai bidang:</p>
                <ul>
                    <li>Teknologi Informasi dan Komputer: 80 judul</li>
                    <li>Teknik Elektro dan Telekomunikasi: 60 judul</li>
                    <li>Teknik Mesin dan Industri: 50 judul</li>
                    <li>Manajemen Bisnis: 40 judul</li>
                    <li>Referensi Umum: 20 judul</li>
                </ul>
                <p>Semua buku baru telah dikatalog dan siap untuk dipinjam. Sivitas akademika dapat menelusuri koleksi baru melalui OPAC dengan filter "Koleksi Terbaru 2026".</p>
                <p>Perpustakaan juga membuka usulan koleksi buku untuk periode berikutnya melalui fitur "Usulan Koleksi Buku" di portal perpustakaan.</p>',
                'tanggal_berita' => Carbon::now()->subDays(7),
                'user_id_author' => 1,
                'status_berita' => 'published',
                'meta_desc_berita' => 'Perpustakaan PCR menambahkan 250 judul buku terbaru dari berbagai bidang ilmu pada Januari 2026',
                'meta_keyword_berita' => 'koleksi buku, buku baru, perpustakaan, PCR, OPAC',
                'slug_berita' => 'penambahan-koleksi-buku-terbaru-di-perpustakaan-pcr',
                'filename_berita' => 'perpus-3.jpg',
                'created_by' => '1',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
        ];

        foreach ($beritaData as $berita) {
            DB::table('berita')->insert($berita);
        }
    }
}

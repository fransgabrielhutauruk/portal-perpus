<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Dimension\Jurusan;

class HeaderMenuComposer
{
    public function compose(View $view)
    {
        $menu = [
            [
                'name'  => 'Beranda',
                'route' => route('frontend.home'),
            ],
            [
                'name'     => 'Tentang Kami',
                'children' => [
                    ['name' => 'Sejarah Perpustakaan', 'route' => route('frontend.home')],
                    ['name' => 'Visi dan Misi', 'route' => route('frontend.home')],
                    ['name' => 'Struktur Organisasi', 'route' => route('frontend.home')],
                    ['name' => 'Pustakawan', 'route' => route('frontend.home')],
                    ['name' => 'Jam Buka Layanan', 'route' => route('frontend.home')],
                ],
            ],
            [
                'name'     => 'Akses dan Koleksi',
                'children' => [
                    ['name' => 'OPAC', 'route' => 'https://lib.pcr.ac.id/'],
                    ['name' => 'Repository', 'route' => 'https://baak.pcr.ac.id/kalender-akademik/'],
                    ['name' => 'ISBN Penerbit PCR', 'route' => route('frontend.home')],
                    ['name' => 'E-Journal PCR', 'route' => route('frontend.home')],
                    ['name' => 'Jurnal Tercetak', 'route' => route('frontend.home')],
                    ['name' => 'E-book Langganan', 'route' => route('frontend.home')],
                ],
            ],
            ['name' => 'Fasilitas', 'route' => route('frontend.home')],
            [
                'name'     => 'Layanan',
                'children' => [
                    ['name' => 'Usulan Koleksi Buku', 'route' =>  route('frontend.req.buku')],
                    ['name' => 'Kebutuhan Modul Semester', 'route' => route('frontend.req.modul')],
                    ['name' => 'Cek Turnitin', 'route' => route('frontend.req.turnitin')],
                    ['name' => 'Surat Bebas Pustaka', 'route' => route('frontend.req.bebas-pustaka')],
                ],
            ],
            ['name' => 'Panduan', 'route' => route('frontend.home')],
            ['name' => 'Berita', 'route' => route('frontend.berita.index')],
            ['name' => 'FAQ', 'route' => route('frontend.home')],
        ];

        $view->with('menu', $menu);
    }
}

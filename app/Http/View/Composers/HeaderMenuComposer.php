<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Dimension\Jurusan;
use App\Services\Frontend\PanduanService;

class HeaderMenuComposer
{
    public function compose(View $view)
    {
        // Get panduan list for menu
        $panduanMenuItems = PanduanService::getPanduanForMenu();
        
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
                    ['name' => 'OPAC', 'route' => 'https://opac.lib.pcr.ac.id/', 'target' => '_blank'],
                    ['name' => 'Repository', 'route' => 'https://repository.lib.pcr.ac.id/', 'target' => '_blank'],
                    ['name' => 'ISBN Penerbit PCR', 'route' => 'https://isbn.lib.pcr.ac.id', 'target' => '_blank'],
                    ['name' => 'E-Journal PCR', 'route' => 'https://jurnal.pcr.ac.id', 'target' => '_blank'],
                    ['name' => 'Jurnal Tercetak', 'route' => 'https://opac.lib.pcr.ac.id/index.php?keywords=jurnal&search=search', 'target' => '_blank'],
                    ['name' => 'E-book Langganan', 'route' => 'https://www.emerald.com/insight/', 'target' => '_blank'],
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
            [
                'name'     => 'Panduan',
                'route'    => route('frontend.panduan.index'),
                'children' => $panduanMenuItems,
            ],
            ['name' => 'Berita', 'route' => route('frontend.berita.index')],
            ['name' => 'FAQ', 'route' => route('frontend.home')],
        ];

        $view->with('menu', $menu);
    }
}

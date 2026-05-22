<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\AksesKoleksi;

class HeaderMenuComposer
{
    public function compose(View $view)
    {
        $aksesKoleksiList = AksesKoleksi::query()
            ->select(['akses_koleksi_id', 'nama_akses_koleksi', 'url', 'urutan'])
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->akses_koleksi_id,
                    'route' => $item->url,
                    'name' => $item->nama_akses_koleksi,
                    'target' => '_blank',
                ];
            })
            ->values()
            ->all();

        $menu = [
            [
                'name'  => 'Beranda',
                'route' => route('frontend.home'),
            ],
            [
                'name'     => 'Tentang Kami',
                'children' => [
                    ['name' => 'Profil Perpustakaan', 'route' => route('frontend.about.profil')],
                    ['name' => 'Pustakawan', 'route' => route('frontend.about.pustakawan')],
                    ['name' => 'Jam Buka Layanan', 'route' => route('frontend.about.jam-buka')],
                ],
            ],
            [
                'name'     => 'Akses dan Koleksi',
                'children' => $aksesKoleksiList,
            ],
            [
                'name'     => 'Layanan',
                'children' => [
                    ['name' => 'Usulan Koleksi Buku', 'route' =>  route('frontend.req.buku')],
                    ['name' => 'Kebutuhan Modul Semester', 'route' => route('frontend.req.modul')],
                    ['name' => 'Cek Plagiarisme', 'route' => route('frontend.req.turnitin')],
                    ['name' => 'Surat Bebas Pustaka', 'route' => route('frontend.req.bebas-pustaka')],
                ],
            ],
            ['name' => 'Panduan', 'route' => route('frontend.panduan.index')],
            ['name' => 'Berita', 'route' => route('frontend.berita.index')],
            ['name' => 'FAQ', 'route' => route('frontend.faq.index')],
        ];

        $view->with('menu', $menu);
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class InformationController extends Controller
{
    public function profilPerpustakaan()
    {
        $pageConfig = [
            'seo' => [
                'title' => 'Profil Perpustakaan',
                'description' => 'Profil dan sejarah Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.profil', compact('pageConfig'));
    }

    public function pustakawan()
    {
        $pustakawanList = \App\Models\Pustakawan::select(['pustakawan_id', 'nama', 'email', 'foto'])
            ->whereNull('deleted_at')
            ->orderBy('nama', 'ASC')
            ->get();

        $pageConfig = [
            'seo' => [
                'title' => 'Pustakawan - Perpustakaan PCR',
                'description' => 'Tim Pustakawan Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.pustakawan', compact('pageConfig', 'pustakawanList'));
    }

    public function jamBuka()
    {
        $jadwal = [
            [
                'hari' => 'Senin - Kamis',
                'jam' => '08.00 - 16.00 WIB',
                'icon' => 'fa-solid fa-calendar-days',
                'color' => 'primary'
            ],
            [
                'hari' => 'Jumat',
                'jam' => '08.00 - 16.30 WIB',
                'icon' => 'fa-solid fa-calendar-day',
                'color' => 'success'
            ],
            [
                'hari' => 'Sabtu - Minggu',
                'jam' => 'Libur',
                'icon' => 'fa-solid fa-calendar-xmark',
                'color' => 'danger'
            ]
        ];

        $pageConfig = [
            'seo' => [
                'title' => 'Jam Buka Layanan - Perpustakaan PCR',
                'description' => 'Jam Buka Layanan Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.jam-buka', compact('pageConfig', 'jadwal'));
    }
}

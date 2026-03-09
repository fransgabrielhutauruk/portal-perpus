<?php

namespace App\Services\Frontend;

use App\Models\Pustakawan;

class InformationService
{
    /**
     * Get all pustakawan for frontend display
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getPustakawanList()
    {
        try {
            return Pustakawan::select(['pustakawan_id', 'nama', 'email', 'foto'])
                ->whereNull('deleted_at')
                ->orderBy('nama', 'ASC')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get jam buka (opening hours) schedule
     *
     * @return array
     */
    public static function getJamBukaSchedule(): array
    {
        return [
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
    }

    /**
     * Get page configuration for information pages
     *
     * @param string $page (profil|pustakawan|jam-buka)
     * @return array
     */
    public static function getPageConfig(string $page = 'profil'): array
    {
        $configs = [
            'profil' => [
                'title' => 'Profil Perpustakaan',
                'description' => 'Profil dan sejarah Perpustakaan Politeknik Caltex Riau',
                'keywords' => 'Profil, Perpustakaan, PCR, About, Sejarah',
            ],
            'pustakawan' => [
                'title' => 'Pustakawan - Perpustakaan PCR',
                'description' => 'Tim Pustakawan Perpustakaan Politeknik Caltex Riau',
                'keywords' => 'Pustakawan, Librarian, Tim, Staff, PCR',
            ],
            'jam-buka' => [
                'title' => 'Jam Buka Layanan - Perpustakaan PCR',
                'description' => 'Jam Buka Layanan Perpustakaan Politeknik Caltex Riau',
                'keywords' => 'Jam Buka, Opening Hours, Jadwal, Schedule, PCR',
            ],
        ];

        $config = $configs[$page] ?? $configs['profil'];

        return [
            'seo' => [
                'title' => $config['title'],
                'description' => $config['description'],
                'keywords' => $config['keywords'] ?? null,
                'canonical' => route('frontend.about.' . $page),
                'og_image' => null,
                'og_type' => 'website',
            ]
        ];
    }

    /**
     * Get content data for profil page
     *
     * @return array
     */
    public static function getProfilContent(): array
    {
        return [
            'header' => 'Profil Perpustakaan',
            'title' => 'Tentang Perpustakaan PCR',
            'subtitle' => 'Mengenal Lebih Dekat',
            'description' => 'Perpustakaan Politeknik Caltex Riau menyediakan berbagai fasilitas dan layanan untuk mendukung kegiatan akademik.',
        ];
    }

    /**
     * Get content data for pustakawan page
     *
     * @return array
     */
    public static function getPustakawanContent(): array
    {
        return [
            'header' => 'Pustakawan',
            'title' => 'Tim Pustakawan',
            'subtitle' => 'Berkenalan dengan Tim Kami',
            'description' => 'Tim pustakawan profesional yang siap membantu Anda dalam mengakses berbagai sumber informasi dan layanan perpustakaan.',
        ];
    }

    /**
     * Get content data for jam buka page
     *
     * @return array
     */
    public static function getJamBukaContent(): array
    {
        return [
            'header' => 'Jam Buka',
            'title' => 'Jam Operasional',
            'subtitle' => 'Waktu Layanan',
            'description' => 'Perpustakaan Politeknik Caltex Riau melayani dengan jadwal berikut.',
        ];
    }
}

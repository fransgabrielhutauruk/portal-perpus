<?php

namespace App\Services\Frontend;

use App\Models\Panduan;

class PanduanService
{
    /**
     * Get all panduan for frontend display
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllPanduan()
    {
        try {
            return Panduan::select(['panduan_id', 'judul', 'deskripsi', 'file'])
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'DESC')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get panduan for menu composer
     *
     * @return array
     */
    public static function getPanduanForMenu(): array
    {
        $panduanList = self::getAllPanduan();
        
        $menuItems = [];
        foreach ($panduanList as $panduan) {
            $menuItems[] = [
                'name' => $panduan->judul,
                'route' => route('frontend.panduan.show', ['panduanId' => $panduan->panduan_id]),
                'target' => '_blank'
            ];
        }
        
        return $menuItems;
    }

    /**
     * Get content data for panduan index page
     *
     * @return array
     */
    public static function getIndexContent(): array
    {
        $panduanList = self::getAllPanduan();
        
        return [
            'header' => 'Panduan',
            'title' => 'Panduan Perpustakaan Politeknik Caltex Riau',
            'subtitle' => '',
            'description' => 'Kumpulan panduan dan tutorial penggunaan layanan perpustakaan PCR',
            'panduan_list' => $panduanList,
        ];
    }

    /**
     * Get single panduan by ID
     *
     * @param int $panduanId
     * @return \App\Models\Panduan|null
     */
    public static function getPanduanById($panduanId)
    {
        try {
            return Panduan::where('panduan_id', $panduanId)
                ->whereNull('deleted_at')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get meta data for Panduan page (SEO)
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title' => 'Panduan Perpustakaan Politeknik Caltex Riau',
            'description' => 'Panduan dan tutorial lengkap untuk menggunakan layanan dan fasilitas perpustakaan PCR',
            'keywords' => 'Panduan, Tutorial, Perpustakaan, PCR, Layanan'
        ];
    }

    /**
     * Get page configuration for panduan page
     *
     * @param object|null $panduanData
     * @return array
     */
    public static function getPageConfig($panduanData = null): array
    {
        $meta = self::getMetaData();

        return [
            'background_image' => null,
            'seo' => [
                'title' => $panduanData ? $panduanData->judul : $meta['title'],
                'description' => $panduanData && $panduanData->deskripsi ? $panduanData->deskripsi : $meta['description'],
                'keywords' => $meta['keywords'],
                'canonical' => $panduanData ? route('frontend.panduan.show', ['panduanId' => $panduanData->panduan_id]) : route('frontend.panduan.index'),
                'og_image' => null,
                'og_type' => 'website',
            ]
        ];
    }
}

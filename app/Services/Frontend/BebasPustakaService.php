<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\BebasPustaka; // <--- Changed Model
use Carbon\Carbon;

class BebasPustakaService
{
    /**
     * Return content for the Bebas Pustaka page.
     * Fetches Prodi list for the dropdown.
     *
     * @return array
     */
    public static function getContent()
    {
        // 1. Fetch Recent Requests (History)
        $history = self::getRecentRequests();
        
        // Note: Bebas Pustaka might not depend on a specific "Period" like book proposals.
        // If it's always open, we can just set is_open to true.
        // However, if you want it tied to a graduation period, keep this logic.
        // For now, I will assume it is always open or tied to a generic period logic if needed.
        
        // OPTION A: Always Open (Common for Bebas Pustaka)
        $isOpen = true;
        $periodeName = 'Periode Wisuda Aktif'; 

        // OPTION B: Use mst_periode (Uncomment if needed)
        /*
        $activePeriode = DB::table('mst_periode')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->first();
        $isOpen = $activePeriode ? true : false;
        $periodeName = $activePeriode ? $activePeriode->nama_periode : '-';
        */

        // 2. Fetch Prodi List
        $prodiList = DB::table('dm_prodi')
            ->select('prodi_id', 'nama_prodi')
            ->orderBy('nama_prodi', 'asc')
            ->get();
        return [
            'header'        => 'Bebas Pustaka',
            'title'         => 'Pengajuan Surat Bebas Pustaka',
            'subtitle'      => 'Layanan Administrasi Perpustakaan',
            'description'   => 'Lengkapi persyaratan administrasi perpustakaan Anda untuk keperluan wisuda atau yudisium.',
            
            // Period Status
            'is_open'        => $isOpen,
            'periode_name'   => $periodeName,
            
            'history' => $history, 
            'opac_url' => 'https://lib.pcr.ac.id',

            'prodi_list'    => $prodiList,
            'form'          => [
                // Ensure you have this route defined
                'action_url' => route('frontend.bebas-pustaka.submit'), 
            ]
        ];
    }

    public static function getRecentRequests($limit = 5)
    {
        try {
            // --- Matching 'req_bebas_pustaka' table structure ---
            return BebasPustaka::select(
                    'nama_mahasiswa', 
                    'nim', 
                    'prodi_id', // Might want to join with prodi table for name, or handle in view
                    'is_syarat_terpenuhi',
                    'created_at', 
                    'status'
                )
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Optional metadata for SEO
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title'         => 'Bebas Pustaka - Perpustakaan PCR', 
            'description'   => 'Halaman pengajuan surat bebas pustaka perpustakaan Politeknik Caltex Riau.',
            'keywords'      => 'bebas pustaka, surat keterangan bebas pustaka, perpustakaan pcr, wisuda'
        ];
    }

    /**
     * Page config (Background images, SEO, OpenGraph)
     *
     * @return array
     */
    public static function getPageConfig()
    {
        $meta = self::getMetaData();

        // Ensure this image exists or change to a relevant one
        $bg = publicMedia('bebas-pustaka-bg.webp', 'banners'); 

        return [
            'background_image' => $bg,
            'seo' => [
                'title'         => $meta['title'],
                'description'   => $meta['description'],
                'keywords'      => $meta['keywords'],
                'canonical'     => route('frontend.bebas-pustaka.index'), // Check route name
                'og_image'      => $bg,
                'og_type'       => 'website',
                'structured_data' => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Structured Data (JSON-LD)
     */
    public static function getStructuredData(string $bg): array
    {
        $meta = self::getMetaData();
        return [
            '@context'      => 'https://schema.org',
            '@type'         => 'WebPage',
            'name'          => $meta['title'],
            'description'   => $meta['description'],
            'image'         => $bg,
            'url'           => route('frontend.bebas-pustaka.index')
        ];
    }

    /**
     * Breadcrumb Data
     */
    public static function getBreadcrumbStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => route('frontend.home')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Bebas Pustaka',
                    'item' => route('frontend.bebas-pustaka.index')
                ]
            ]
        ];
    }
}
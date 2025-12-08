<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\UsulanModul; // <--- Changed from Usulan to UsulanModul
use Carbon\Carbon; // <--- Corrected Carbon usage

class UsulanModulService
{
    /**
     * Return content for the Usulan Modul page. // <--- Changed description
     * Fetches Prodi list for the dropdown.
     *
     * @return array
     */
    public static function getContent()
    {
        // 1. Fetch the Active Period based on today's date
        $history = self::getRecentProposals();
        
        $activePeriode = DB::table('mst_periode')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->first(); // Gets the row object or null
        
        // 1. Check what the DB actually contains
        $allPeriodes = DB::table('mst_periode')->get();
        

    
        // 2. Fetch Prodi List
        $prodiList = DB::table('dm_prodi')
            ->select('prodi_id', 'nama_prodi')
            ->orderBy('nama_prodi', 'asc')
            ->get();

        return [
            'header'        => 'Request Modul', // <--- Changed text
            'title'         => 'Request Pengadaan Modul/Buku', // <--- Changed text
            'subtitle'      => 'Sistem Layanan Perpustakaan',
            'description'   => 'Lengkapi koleksi modul mata kuliah Anda melalui formulir permintaan ini.', // <--- Changed text
            
            // Pass the period status to the view
            'active_periode' => $activePeriode, // Will be null if no period is open
            'is_open'        => $activePeriode ? true : false,
            'periode_name'   => $activePeriode ? $activePeriode->nama_periode : '-',
            
            'history' => $history, // <--- Pass history data
            'opac_url' => 'https://lib.pcr.ac.id', // Example OPAC URL

            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.usulan.sendUsulanModul'),
            ]
        ];
    }

    public static function getRecentProposals($limit = 5)
    {
        try {
            // --- Changed Model and selected columns to match the 'req_modul' table structure ---
            return UsulanModul::select(
                    'judul_modul', 
                    'nama_mata_kuliah', // New relevant column for display
                    'nama_dosen',       // Changed from nama_req
                    'inisial_dosen',    // New relevant column for display
                    'praktikum',     // New relevant column for display
                    'created_at', 
                    'status'            // Changed from status_req
                )
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            // Log::error($e->getMessage()); // Should ideally log the error
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
            'title'         => 'Request Modul - Perpustakaan PCR', // <--- Changed text
            'description'   => 'Halaman pengajuan request modul/buku perpustakaan Politeknik Caltex Riau.', // <--- Changed text
            'keywords'      => 'request modul, usulan modul, perpustakaan pcr, pengadaan modul' // <--- Changed text
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

        $bg = publicMedia('usulan-bg.webp', 'banners'); 

        return [
            'background_image' => $bg,
            'seo' => [
                'title'         => $meta['title'],
                'description'   => $meta['description'],
                'keywords'      => $meta['keywords'],
                'canonical'     => route('frontend.usulan.usulan-modul'),
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
            'url'           => route('frontend.usulan.usulan-modul')
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
                    'name' => 'Request Modul', // <--- Changed text
                    'item' => route('frontend.usulan.usulan-modul')
                ]
            ]
        ];
    }
}
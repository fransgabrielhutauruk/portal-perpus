<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\Usulan;
use Carbon;

class UsulanService
{
    /**
     * Return content for the Usulan Buku page.
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

    // 2. Fetch Prodi List
    $prodiList = DB::table('dm_prodi')
        ->select('prodi_id', 'nama_prodi')
        ->orderBy('nama_prodi', 'asc')
        ->get();

    return [
        'header'      => 'Usulan Buku',
        'title'       => 'Usulan Pengadaan Buku',
        'subtitle'    => 'Sistem Layanan Perpustakaan',
        'description' => 'Partisipasi aktif Anda membantu kami melengkapi koleksi perpustakaan.',
        
        // Pass the period status to the view
        'active_periode' => $activePeriode, // Will be null if no period is open
        'is_open'        => $activePeriode ? true : false,
        'periode_name'   => $activePeriode ? $activePeriode->nama_periode : '-',
        
        'history' => $history, // <--- Pass history data
        'opac_url' => 'https://lib.pcr.ac.id', // Example OPAC URL

        'prodi_list'  => $prodiList,
        'form'        => [
            'action_url' => route('frontend.usulan.sendUsulan'),
        ]
    ];
}

public static function getRecentProposals($limit = 5)
{
    try {

        return Usulan::select('judul_buku', 'penulis_buku', 'nama_req', 'created_at', 'status_req')
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
            'title'       => 'Usulan Buku - Perpustakaan PCR',
            'description' => 'Halaman pengajuan usulan pengadaan buku perpustakaan Politeknik Caltex Riau.',
            'keywords'    => 'usulan buku, request buku, perpustakaan pcr, pengadaan'
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
                'title'       => $meta['title'],
                'description' => $meta['description'],
                'keywords'    => $meta['keywords'],
                'canonical'   => route('frontend.usulan.usulan-buku'),
                'og_image'    => $bg,
                'og_type'     => 'website',
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
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            'name'        => $meta['title'],
            'description' => $meta['description'],
            'image'       => $bg,
            'url'         => route('frontend.usulan.usulan-buku')
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
                    'name' => 'Usulan Buku',
                    'item' => route('frontend.usulan.usulan-buku')
                ]
            ]
        ];
    }
}
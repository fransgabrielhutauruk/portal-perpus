<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\ReqModul;
use Carbon\Carbon;

class ReqModulService
{
    /**
     * Return content for the Usulan Modul page.
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
            'header'        => 'Kebutuhan Modul Semester',
            'title'         => 'Request Pengadaan Modul/Buku',
            'subtitle'      => 'Ajukan Kebutuhan Modul Sebagai Pengampu Mata Kuliah',
            'description'   => 'Lengkapi koleksi modul mata kuliah Anda melalui formulir permintaan ini.',

            // Pass the period status to the view
            'active_periode' => $activePeriode, // Will be null if no period is open
            'is_open'        => $activePeriode ? true : false,
            'periode_name'   => $activePeriode ? $activePeriode->nama_periode : '-',

            'history' => $history, // <--- Pass history data
            'opac_url' => 'https://lib.pcr.ac.id', // Example OPAC URL

            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.req.modul.send'),
            ]
        ];
    }

    public static function getRecentProposals($limit = 5)
    {
        try {
            return ReqModul::select(
                'judul_modul',
                'nama_mata_kuliah',
                'nama_dosen',
                'inisial_dosen',
                'praktikum',
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
            'title'         => 'Kebutuhan Modul Semester - Perpustakaan PCR',
            'description'   => 'Halaman pengajuan request modul/buku perpustakaan Politeknik Caltex Riau.',
            'keywords'      => 'request modul, usulan modul, perpustakaan pcr, pengadaan modul'
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
        $bg = publicMedia('perpus-2.jpg', 'perpus');

        return [
            'background_image' => $bg,
            'seo' => [
                'title'         => $meta['title'],
                'description'   => $meta['description'],
                'keywords'      => $meta['keywords'],
                'canonical'     => route('frontend.req.modul'),
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
            'url'           => route('frontend.req.modul')
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
                    'name' => 'Request Modul',
                    'item' => route('frontend.req.modul')
                ]
            ]
        ];
    }
}

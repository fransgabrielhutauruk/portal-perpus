<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\ReqBebasPustaka;
use Carbon\Carbon;

class ReqBebasPustakaService
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

        // Fetch the active periode for Bebas Pustaka
        $activePeriode = DB::table('mst_periode')
            ->where('jenis_periode', 'req_bebas_pustaka')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereNull('deleted_at')
            ->first();
        
        $isOpen = $activePeriode ? true : false;
        $periodeName = $activePeriode ? $activePeriode->nama_periode : '-';


        // 2. Fetch Prodi List
        $prodiList = DB::table('dm_prodi')
            ->select('prodi_id', 'nama_prodi')
            ->orderBy('nama_prodi', 'asc')
            ->get();
        return [
            'header'        => 'Surat Bebas Pustaka',
            'title'         => 'Pengajuan Surat Bebas Pustaka',
            'subtitle'      => 'Pengajuan Surat Bebas Pustaka',
            'description'   => 'Lengkapi persyaratan administrasi perpustakaan Anda untuk keperluan wisuda atau yudisium.',

            // Period Status
            'is_open'        => $isOpen,
            'periode_name'   => $periodeName,

            'history' => $history,
            'opac_url' => 'https://lib.pcr.ac.id',

            'prodi_list'    => $prodiList,
            'form'          => [
                // Ensure you have this route defined
                'action_url' => route('frontend.req.bebas-pustaka.send'),
            ]
        ];
    }

    public static function getRecentRequests($limit = 5)
    {
        try {
            return ReqBebasPustaka::select(
                'nama_mahasiswa',
                'nim',
                'prodi_id',
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
            'title'         => 'Bebas Pustaka',
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

        $bg = publicMedia('perpus-4.jpg', 'perpus');

        return [
            'background_image' => $bg,
            'seo' => [
                'title'         => $meta['title'],
                'description'   => $meta['description'],
                'keywords'      => $meta['keywords'],
                'canonical'     => route('frontend.req.bebas-pustaka'),
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
            'url'           => route('frontend.req.bebas-pustaka')
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
                    'item' => route('frontend.req.bebas-pustaka')
                ]
            ]
        ];
    }
}

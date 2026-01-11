<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\ReqTurnitin;
use Carbon\Carbon;

class ReqTurnitinService
{
    /**
     * Return content for the Turnitin page.
     * Fetches Prodi list for the dropdown.
     *
     * @return array
     */
    public static function getContent()
    {
        $prodiList = DB::table('dm_prodi')
            ->select('prodi_id', 'nama_prodi')
            ->orderBy('nama_prodi', 'asc')
            ->get();

        return [
            'header'        => 'Cek Turnitin',
            'title'         => 'Pengajuan Cek Turnitin',
            'subtitle'      => 'Ajukan Dokumen untuk Pengecekan Turnitin',
            'description'   => 'Lengkapi formulir berikut untuk mengajukan dokumen yang akan dicek plagiarismenya menggunakan Turnitin.',

            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.req.turnitin.send'),
            ]
        ];
    }

    /**
     * Optional metadata for SEO
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title'         => 'Cek Turnitin - Perpustakaan Politeknik Caltex Riau',
            'description'   => 'Halaman pengajuan cek turnitin perpustakaan Politeknik Caltex Riau.',
            'keywords'      => 'turnitin, cek plagiarisme, perpustakaan pcr, plagiarism check'
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
        $bg = publicMedia('perpus-3.jpg', 'perpus');

        return [
            'background_image' => $bg,
            'seo' => [
                'title'         => $meta['title'],
                'description'   => $meta['description'],
                'keywords'      => $meta['keywords'],
                'canonical'     => route('frontend.req.turnitin'),
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
            'url'           => route('frontend.req.turnitin')
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
                    'name' => 'Cek Turnitin',
                    'item' => route('frontend.req.turnitin')
                ]
            ]
        ];
    }
}

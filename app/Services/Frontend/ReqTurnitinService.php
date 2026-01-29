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
        $history = self::getRecentRequests();
        
        $prodiList = DB::table('dm_prodi')
            ->select('prodi_id', 'nama_prodi')
            ->orderBy('nama_prodi', 'asc')
            ->get();

        return [
            'header'        => 'Cek Plagiarisme',
            'title'         => 'Pengajuan Cek Plagiarisme',
            'subtitle'      => 'Ajukan Dokumen untuk Pengecekan Plagiarisme',
            'description'   => 'Lengkapi formulir berikut untuk mengajukan dokumen yang akan dicek plagiarismenya.',

            'history'       => $history,
            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.req.turnitin.send'),
            ]
        ];
    }

    /**
     * Get recent turnitin requests for history display
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function getRecentRequests($limit = 20)
    {
        try {
            return ReqTurnitin::with('prodi')
                ->select(
                    'reqturnitin_id as id',
                    'nama_dosen',
                    'nip',
                    'prodi_id',
                    'judul_dokumen',
                    'jenis_dokumen',
                    'status_req',
                    'catatan_admin',
                    'created_at'
                )
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    $statusBadges = [
                        0 => '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>',
                        1 => '<span class="badge bg-success rounded-pill">Disetujui</span>',
                        2 => '<span class="badge bg-danger rounded-pill">Ditolak</span>',
                    ];
                    $item->status_badge = $statusBadges[$item->status_req] ?? '<span class="badge bg-secondary rounded-pill">-</span>';
                    return $item;
                });
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
            'title'         => 'Cek Plagiarisme',
            'description'   => 'Halaman pengajuan cek plagiarisme perpustakaan Politeknik Caltex Riau.',
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
        $bg = publicMedia('perpus-7.webp', 'perpus');

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

<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\ReqBebasPustaka;
use App\Enums\StatusRequest;

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
        $history = self::getRecentRequests();
        $activePeriode = self::getActivePeriode();
        $prodiList = self::getProdiList();
        
        $isOpen = $activePeriode ? true : false;
        $periodeName = $activePeriode ? $activePeriode->nama_periode : '-';

        return [
            'header'        => 'Surat Bebas Pustaka',
            'title'         => 'Pengajuan Surat Bebas Pustaka',
            'subtitle'      => 'Pengajuan Surat Bebas Pustaka',
            'description'   => 'Lengkapi persyaratan administrasi perpustakaan Anda untuk keperluan wisuda atau yudisium.',

            'active_periode' => $activePeriode,
            'is_open'        => $isOpen,
            'periode_name'   => $periodeName,

            'history' => $history,
            'opac_url' => 'https://lib.pcr.ac.id',

            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.req.bebas-pustaka.send'),
            ]
        ];
    }

    /**
     * Get active periode for bebas pustaka
     *
     * @return object|null
     */
    public static function getActivePeriode()
    {
        try {
            return DB::table('mst_periode')
                ->where('jenis_periode', 'req_bebas_pustaka')
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->whereNull('deleted_at')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get list of program studi for dropdown
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getProdiList()
    {
        try {
            return DB::table('dm_prodi')
                ->select('prodi_id', 'nama_prodi')
                ->orderBy('nama_prodi', 'asc')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Prepare data for bebas pustaka submission
     *
     * @param array $validatedData
     * @param int $periodeId
     * @return array
     */
    public static function prepareUsulanData(array $validatedData, int $periodeId): array
    {
        return [
            'periode_id'               => $periodeId,
            'nama_mahasiswa'           => $validatedData['nama_mahasiswa'],
            'email_mahasiswa'          => $validatedData['email_mahasiswa'],
            'nim'                      => $validatedData['nim'],
            'prodi_id'                 => $validatedData['prodi_id'],
            'link_kp_repository'       => $validatedData['link_kp_repository'],
            'link_pa_repository'       => $validatedData['link_pa_repository'],
            'is_syarat_terpenuhi'      => false,
            'status_req'               => StatusRequest::MENUNGGU->value,
            'catatan_admin'            => null,
            'file_hasil_bebas_pustaka' => null
        ];
    }

    /**
     * Submit bebas pustaka request
     *
     * @param array $validatedData
     * @return array
     */
    public static function submitUsulan(array $validatedData): array
    {
        try {
            // Check if periode is open
            $activePeriode = self::getActivePeriode();
            
            if (!$activePeriode) {
                return [
                    'success'      => false,
                    'message'      => 'Periode pengajuan bebas pustaka sedang tidak dibuka. Silakan hubungi admin perpustakaan.',
                    'status_code'  => 403,
                    'data'         => []
                ];
            }

            DB::beginTransaction();

            $data = self::prepareUsulanData($validatedData, $activePeriode->periode_id);
            $reqBebasPustaka = ReqBebasPustaka::create($data);
            $reqBebasPustaka->load('prodi');

            DB::commit();

            return [
                'success'      => true,
                'message'      => 'Pengajuan Surat Bebas Pustaka berhasil dikirim!',
                'status_code'  => 200,
                'data'         => [
                    'nama_mahasiswa' => $reqBebasPustaka->nama_mahasiswa,
                    'nim'            => $reqBebasPustaka->nim,
                    'prodi_nama'     => $reqBebasPustaka->prodi->nama_prodi ?? '-',
                    'date_fmt'       => tanggal($reqBebasPustaka->created_at, ' '),
                    'status_req'     => $reqBebasPustaka->status_req
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'      => false,
                'message'      => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code'  => 500,
                'data'         => []
            ];
        }
    }

    public static function getRecentRequests($limit = 20)
    {
        try {
            return ReqBebasPustaka::with('prodi')
                ->select(
                    'reqbebaspustaka_id as id',
                    'nama_mahasiswa',
                    'nim',
                    'prodi_id',
                    'is_syarat_terpenuhi',
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

        $bg = publicMedia('perpus-1.webp', 'perpus');

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

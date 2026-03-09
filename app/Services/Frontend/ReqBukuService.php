<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\ReqBuku;

class ReqBukuService
{
    /**
     * Get the active periode for req_buku
     *
     * @return object|null
     */
    public static function getActivePeriode()
    {
        try {
            return DB::table('mst_periode')
                ->where('jenis_periode', 'req_buku')
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->whereNull('deleted_at')
                ->orderByDesc('created_at')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get list of Prodi for dropdown
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
     * Return content for the Usulan Buku page.
     * Fetches Prodi list for the dropdown.
     *
     * @return array
     */
    public static function getContent()
    {
        $activePeriode = self::getActivePeriode();
        $history = self::getRecentProposals();
        $prodiList = self::getProdiList();

        return [
            'header'      => 'Usulan Buku',
            'subtitle'    => 'Kirim Usulan Buku',

            // Pass the period status to the view
            'active_periode' => $activePeriode,
            'is_open'        => $activePeriode ? true : false,
            'periode_name'   => $activePeriode ? $activePeriode->nama_periode : '-',

            'history' => $history,
            'opac_url' => 'https://opac.lib.pcr.ac.id/',

            'prodi_list'  => $prodiList,
            'form'        => [
                'action_url' => route('frontend.req.buku.send'),
            ]
        ];
    }

    /**
     * Get recent proposals with limited results
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function getRecentProposals($limit = 10)
    {
        try {
            return ReqBuku::select('judul_buku', 'penulis_buku', 'nama_req', 'created_at', 'status_req', 'catatan_admin')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Transform and prepare data for creating ReqBuku
     *
     * @param array $validatedData
     * @param int $periodeId
     * @return array
     */
    public static function prepareUsulanData(array $validatedData, int $periodeId): array
    {
        $penerbitList = isset($validatedData['penerbit']) 
            ? implode(', ', array_filter($validatedData['penerbit'])) 
            : '-';

        $jenisBukuList = implode(', ', $validatedData['jenis_buku']);

        $bahasaBuku = $validatedData['bahasa_buku'] === 'inggris' ? 'Inggris' : 'Indonesia';

        return [
            'periode_id'     => $periodeId,
            'prodi_id'       => $validatedData['prodi_id'],
            'nama_req'       => $validatedData['nama_req'],
            'email_req'      => $validatedData['email_req'],
            'nim'            => $validatedData['nim'] ?? null,
            'nip'            => $validatedData['nip'] ?? null,
            'judul_buku'     => $validatedData['judul_buku'],
            'penulis_buku'   => $validatedData['penulis_buku'],
            'tahun_terbit'   => $validatedData['tahun_terbit'],
            'penerbit_buku'  => $penerbitList,
            'jenis_buku'     => $jenisBukuList,
            'bahasa_buku'    => $bahasaBuku,
            'estimasi_harga' => $validatedData['estimasi_harga'] ?? null,
            'link_pembelian' => $validatedData['link_pembelian'],
            'alasan_usulan'  => $validatedData['alasan_usulan'],
            'status_req'     => 0,
        ];
    }

    /**
     * Submit usulan buku with transaction handling
     *
     * @param array $validatedData
     * @return array
     */
    public static function submitUsulan(array $validatedData): array
    {
        $activePeriode = self::getActivePeriode();

        if (!$activePeriode) {
            return [
                'success' => false,
                'message' => 'Periode pengajuan usulan buku sedang tidak dibuka. Silakan hubungi admin perpustakaan.',
                'status_code' => 403
            ];
        }

        try {
            DB::beginTransaction();

            $usulanData = self::prepareUsulanData($validatedData, $activePeriode->periode_id);
            $usulan = ReqBuku::create($usulanData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Usulan buku berhasil dikirim!',
                'status_code' => 200,
                'data' => [
                    'judul_buku' => $usulan->judul_buku,
                    'penulis_buku' => $usulan->penulis_buku,
                    'nama_req' => $usulan->nama_req,
                    'date_fmt' => tanggal($usulan->created_at, ' '),
                    'status_req' => $usulan->status_req
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'status_code' => 500
            ];
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
            'title'       => 'Usulan Buku',
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

        $bg = publicMedia('perpus-2.webp', 'perpus');

        return [
            'background_image' => $bg,
            'seo' => [
                'title'       => $meta['title'],
                'description' => $meta['description'],
                'keywords'    => $meta['keywords'],
                'canonical'   => route('frontend.req.buku'),
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
            'url'         => route('frontend.req.buku')
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
                    'item' => route('frontend.req.buku')
                ]
            ]
        ];
    }
}


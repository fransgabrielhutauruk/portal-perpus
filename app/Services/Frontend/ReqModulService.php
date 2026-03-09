<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ReqModul;
use App\Enums\StatusRequest;

class ReqModulService
{
    /**
     * Get the active periode for req_modul
     *
     * @return object|null
     */
    public static function getActivePeriode()
    {
        try {
            return DB::table('mst_periode')
                ->where('jenis_periode', 'req_modul')
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
     * Return content for the Usulan Modul page.
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
            'header'        => 'Kebutuhan Modul Semester',
            'title'         => 'Request Pengadaan Modul/Buku',
            'subtitle'      => 'Pengajuan Kebutuhan Modul',
            'description'   => 'Lengkapi koleksi modul mata kuliah Anda melalui formulir permintaan ini.',

            // Pass the period status to the view
            'active_periode' => $activePeriode,
            'is_open'        => $activePeriode ? true : false,
            'periode_name'   => $activePeriode ? $activePeriode->nama_periode : '-',

            'history' => $history,
            'opac_url' => 'https://lib.pcr.ac.id',

            'prodi_list'    => $prodiList,
            'form'          => [
                'action_url' => route('frontend.req.modul.send'),
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
            return ReqModul::select(
                'judul_modul',
                'nama_mata_kuliah',
                'nama_dosen',
                'inisial_dosen',
                'praktikum',
                'created_at',
                'status_req',
                'catatan_admin'
            )
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Handle file upload
     *
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return string|null
     */
    public static function handleFileUpload($file)
    {
        if (!$file) {
            return null;
        }

        try {
            return $file->store('uploads/modul', 'public');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Transform and prepare data for creating ReqModul
     *
     * @param array $validatedData
     * @param int $periodeId
     * @param string|null $filePath
     * @return array
     */
    public static function prepareUsulanData(array $validatedData, int $periodeId, ?string $filePath): array
    {
        return [
            'periode_id'          => $periodeId,
            'prodi_id'            => $validatedData['prodi_id'],
            'nama_dosen'          => $validatedData['nama_dosen'],
            'inisial_dosen'       => $validatedData['inisial_dosen'],
            'email_dosen'         => $validatedData['email_dosen'],
            'nip'                 => $validatedData['nip'],
            'nama_mata_kuliah'    => $validatedData['nama_mata_kuliah'],
            'judul_modul'         => $validatedData['judul_modul'],
            'penulis_modul'       => $validatedData['penulis_modul'],
            'tahun_modul'         => $validatedData['tahun_modul'],
            'praktikum'           => $validatedData['praktikum'],
            'jumlah_dibutuhkan'   => $validatedData['jumlah_dibutuhkan'] ?? 0,
            'deskripsi_kebutuhan' => $validatedData['deskripsi_kebutuhan'] ?? null,
            'file'                => $filePath,
            'status_req'          => StatusRequest::MENUNGGU->value,
        ];
    }

    /**
     * Submit usulan modul with transaction handling
     *
     * @param array $validatedData
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return array
     */
    public static function submitUsulan(array $validatedData, $file = null): array
    {
        $activePeriode = self::getActivePeriode();

        if (!$activePeriode) {
            return [
                'success' => false,
                'message' => 'Periode pengajuan modul sedang tidak dibuka. Silakan hubungi admin perpustakaan.',
                'status_code' => 403
            ];
        }

        $filePath = null;

        try {
            DB::beginTransaction();

            // Handle file upload if exists
            $filePath = self::handleFileUpload($file);

            $usulanData = self::prepareUsulanData($validatedData, $activePeriode->periode_id, $filePath);
            $usulanModul = ReqModul::create($usulanData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pengajuan kebutuhan modul berhasil dikirim!',
                'status_code' => 200,
                'data' => [
                    'judul_modul' => $usulanModul->judul_modul,
                    'nama_mata_kuliah' => $usulanModul->nama_mata_kuliah,
                    'nama_dosen' => $usulanModul->nama_dosen,
                    'date_fmt' => tanggal($usulanModul->created_at, ' '),
                    'praktikum' => $usulanModul->praktikum,
                    'status_req' => $usulanModul->status_req
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded file if transaction fails
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

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
            'title'         => 'Kebutuhan Modul Semester',
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
        $bg = publicMedia('perpus-4.webp', 'perpus');

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


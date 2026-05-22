<?php

namespace App\Services\Frontend;

use App\Models\AksesKoleksi;

class LandingService
{
    /**
     * Get content data for landing page sections
     *
     * @return object|null
     */
    public static function getContent(): array
    {
        return [
            'header'      => '',
            'title'       => 'Beranda',
            'subtitle'    => '',
            'description' => 'Politeknik Caltex Riau (PCR) adalah perguruan tinggi di Riau yang didirikan atas kerja sama Pemerintah Provinsi Riau dengan PT Chevron Pacific Indonesia',
        ];
    }

    /**
     * Get meta data for Landing page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Politeknik Caltex Riau (PCR) adalah perguruan tinggi di Riau yang didirikan atas kerja sama Pemerintah Provinsi Riau dengan PT Chevron Pacific Indonesia',
            'keywords'    => 'PCR,Politeknik,Caltex,Riau,Mahasiswa,Politeknik Riau,Penerimaan Mahasiswa,Politeknik Caltex',
        ];
    }

    /**
     * Get page configuration for Landing page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();

        return [
            'background_image' => null, // No specific background image for this page
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.home'),
                'og_image'                   => data_get(SiteIdentityService::getSiteIdentity(), 'logo_path'),
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData(),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for Landing page
     *
     * @return array
     */
    public static function getStructuredData(): array
    {
        $identy   = SiteIdentityService::getSiteIdentity();
        $metaData = self::getMetaData();

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebSite',
            'headline'    => $metaData['title'],
            'description' => $metaData['description'],
            'name'        => $metaData['title'],
            'publisher'   => [
                '@type' => 'Organization',
                'name'  => data_get($identy, 'name'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => data_get($identy, 'logo_path')
                ]
            ],
            'url'         => url()->current()
        ];
    }

    /**
     * Get breadcrumb structured data for Landing page
     *
     * @return array
     */
    public static function getBreadcrumbStructuredData(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Beranda',
                    'item'     => route('frontend.home')
                ]
            ]
        ];
    }

    /**
     * Get facts and statistics data for landing page
     *
     * @return array Array of statistics with icon, value, label, and animation properties
     */
    public static function getFactsAndStatisticsCallout(): array
    {
        return [
            'title'    => 'Fakta dan Data',
            'description' => 'Perpustakaan Politeknik Caltex Riau hadir sebagai pusat sumber belajar dan informasi yang mendukung kegiatan akademik sivitas akademika. Dengan koleksi yang terus berkembang dan layanan digital yang modern, kami berkomitmen memberikan akses informasi terbaik.',
            'image'       => [
                'src' => publicMedia('perpus-depan.webp', 'perpus'),
                'alt' => 'Perpustakaan Politeknik Caltex Riau'
            ],
            'data'        => [
                [
                    'icon'      => 'fa-solid fa-laptop',
                    'value'     => '10',
                    'label'     => 'Komputer Akses Publik',
                    'important' => true,
                    'delay'     => '0.4s',
                    'counter'   => true
                ],
                [
                    'icon'      => 'fa-solid fa-book',
                    'value'     => '18000',
                    'label'     => 'Koleksi Buku',
                    'important' => false,
                    'delay'     => '0.1s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-chair',
                    'value'     => '70',
                    'label'     => 'Kapasitas Tempat Duduk',
                    'important' => true,
                    'delay'     => '1.0s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-book-open-reader',
                    'value'     => '900',
                    'label'     => 'Peminjaman per Tahun',
                    'important' => false,
                    'delay'     => '1.1s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
            ]
        ];
    }

    /**
     * Get Akses dan Koleksi data for landing page
     *
     * @return array
     */
    public static function getAksesKoleksiData(): array
    {
        $items = AksesKoleksi::query()
            ->select([
                'akses_koleksi_id',
                'nama_akses_koleksi',
                'deskripsi',
                'url',
                'urutan',
            ])
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $list = [];
        foreach ($items as $item) {
            $list[] = [
                'name' => $item->nama_akses_koleksi,
                'description' => $item->deskripsi,
                'url' => $item->url,
                'icon' => 'fa-solid fa-book',
                'target' => '_blank',
            ];
        }

        return [
            'title'       => '<b>Akses</b> dan <b>Koleksi</b>',
            'subtitle'    => 'Sumber Informasi Digital',
            'description' => 'Akses berbagai koleksi digital dan layanan informasi perpustakaan untuk mendukung kegiatan akademik Anda.',
            'list'        => $list,
        ];
    }

    /**
     * Get Fasilitas data for landing page
     *
     * @return array
     */
    public static function getFasilitasData(): array
    {
        return [
            'content' => [
                'title' => 'Fasilitas Perpustakaan',
                'description' => 'Perpustakaan PCR menyediakan berbagai fasilitas modern dan nyaman untuk mendukung kegiatan belajar, penelitian, dan pengembangan diri sivitas akademika.',
                'image' => [
                    'src' => publicMedia('fasilitas.png', 'perpus'),
                    'alt' => 'Fasilitas Perpustakaan PCR'
                ]
            ],
            'highlights' => [
                'Ruang Baca - Area baca dengan kapasitas 70+ tempat duduk yang nyaman',
                'Pojok Internet - Akses internet gratis dengan 10 komputer publik',
                'Pojok BI - Koleksi khusus publikasi Bank Indonesia',
                'Area Diskusi/Kolaborasi - Ruang untuk diskusi kelompok dan kerja tim',
                'Pojok Baca Tenang - Area khusus untuk membaca dengan suasana tenang'
            ],
            'actions' => []
        ];
    }

    /**
     * Get Layanan data for landing page
     *
     * @return array
     */
    public static function getLayananData(): array
    {
        return [
            'content' => [
                'subtitle' => 'Layanan Perpustakaan',
                'title' => '<b>Layanan</b> Sivitas Akademika',
                'description' => 'Perpustakaan PCR menyediakan berbagai layanan untuk memudahkan kebutuhan akademik Anda.'
            ],
            'services' => [
                [
                    'title' => 'Usulan Buku',
                    'description' => 'Ajukan usulan pengadaan buku baru yang dibutuhkan untuk mendukung kegiatan pembelajaran atau hobi Anda.',
                    'icon' => 'fa-solid fa-book',
                    'url' => route('frontend.req.buku'),
                ],
                [
                    'title' => 'Pengajuan Modul',
                    'description' => 'Dosen melakukan permintaan modul semester yang tersedia di perpustakaan untuk mendukung pembelajaran.',
                    'icon' => 'fa-solid fa-file-lines',
                    'url' => route('frontend.req.modul'),
                ],
                [
                    'title' => 'Bebas Pustaka',
                    'description' => 'Proses pengajuan bebas pustaka secara online untuk mahasiswa yang akan yudisium atau keperluan administrasi lainnya.',
                    'icon' => 'fa-solid fa-graduation-cap',
                    'url' => route('frontend.req.bebas-pustaka'),
                ],
                [
                    'title' => 'Cek Plagiarisme',
                    'description' => 'Ajukan permohonan pengecekan plagiarisme untuk karya tulis ilmiah untuk memastikan keaslian karya Anda.',
                    'icon' => 'fa-solid fa-magnifying-glass',
                    'url' => route('frontend.req.turnitin'),
                ]
            ]
        ];
    }

    /**
     * Get Panduan data for landing page
     *
     * @return array
     */
    public static function getPanduanData(): array
    {
        $panduanList = \App\Models\Panduan::select(['panduan_id', 'judul', 'deskripsi', 'file'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

        $guides = [];
        foreach ($panduanList as $panduan) {
            $guides[] = [
                'id' => $panduan->panduan_id,
                'title' => $panduan->judul,
                'description' => $panduan->deskripsi ?? 'Panduan lengkap untuk membantu Anda.',
                'file' => $panduan->file,
                'url' => route('frontend.panduan.show', ['panduanId' => $panduan->panduan_id]),
            ];
        }

        return [
            'content' => [
                'subtitle' => 'Panduan Perpustakaan',
                'title' => '<b>Panduan</b> Terkait Perpustakaan',
                'description' => 'Unduh panduan praktis untuk membantu Anda memaksimalkan penggunaan layanan dan fasilitas perpustakaan PCR.'
            ],
            'guides' => $guides
        ];
    }
}

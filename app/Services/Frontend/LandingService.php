<?php

namespace App\Services\Frontend;

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
                'src' => publicMedia('perpus-depan-2.webp', 'perpus'),
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
        return [
            'title'       => '<b>Akses</b> dan <b>Koleksi</b>',
            'subtitle'    => 'Sumber Informasi Digital',
            'description' => 'Akses berbagai koleksi digital dan layanan informasi perpustakaan untuk mendukung kegiatan akademik Anda.',
            'list'        => [
                [
                    'name'   => 'OPAC',
                    'description' => 'Katalog online untuk mencari dan menelusuri koleksi buku, jurnal, dan bahan pustaka lainnya.',
                    'url'    => 'https://opac.lib.pcr.ac.id/',
                    'icon'   => 'fa-solid fa-magnifying-glass',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'Repository',
                    'description' => 'Repositori institusi untuk mengakses karya ilmiah mahasiswa PCR.',
                    'url'    => 'https://repository.lib.pcr.ac.id/',
                    'icon'   => 'fa-solid fa-book-open',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'ISBN Penerbit PCR',
                    'description' => 'Layanan penerbitan dan pengelolaan ISBN untuk karya ilmiah yang diterbitkan PCR.',
                    'url'    => 'https://isbn.lib.pcr.ac.id',
                    'icon'   => 'fa-solid fa-barcode',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'E-Journal PCR',
                    'description' => 'Portal jurnal elektronik yang diterbitkan oleh Politeknik Caltex Riau.',
                    'url'    => 'https://jurnal.pcr.ac.id',
                    'icon'   => 'fa-solid fa-file-lines',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'Jurnal Tercetak',
                    'description' => 'Koleksi jurnal tercetak yang tersedia di perpustakaan PCR.',
                    'url'    => 'https://opac.lib.pcr.ac.id/index.php?keywords=jurnal&search=search',
                    'icon'   => 'fa-solid fa-newspaper',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'E-book Langganan',
                    'description' => 'Akses koleksi e-book berlangganan dari penerbit internasional melalui platform Emerald.',
                    'url'    => 'https://www.emerald.com/insight/',
                    'icon'   => 'fa-solid fa-tablet-screen-button',
                    'target' => '_blank',
                ]
            ]
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
                'Ruang Baca - Area baca dengan kapasitas 50+ tempat duduk yang nyaman',
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
                    'description' => 'Ajukan usulan pengadaan buku baru yang dibutuhkan untuk mendukung kegiatan pembelajaran dan penelitian Anda.',
                    'icon' => 'fa-solid fa-book',
                    'url' => route('frontend.req.buku'),
                ],
                [
                    'title' => 'Pengajuan Modul',
                    'description' => 'Lakukan permintaan modul semester yang tersedia di perpustakaan untuk mendukung pembelajaran.',
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

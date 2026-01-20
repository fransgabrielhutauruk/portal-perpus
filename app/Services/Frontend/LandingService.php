<?php

namespace App\Services\Frontend;

use App\Models\Dimension\Jurusan;
use App\Models\Konten\Konten;

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
     * Get infografis image path for landing page
     *
     * @return string
     */
    public static function getInfografisImage(): string
    {
        return publicMedia('info-grafis.jpg');
    }

    /**
     * Get facts and statistics data for landing page
     *
     * @return array Array of statistics with icon, value, label, and animation properties
     */
    public static function getFactsAndStatisticsCallout(): array
    {
        return [
            'title'       => '<b>Statistik</b> Perpustakaan PCR',
            'subtitle'    => 'Fakta dan Data',
            'description' => 'Perpustakaan Politeknik Caltex Riau hadir sebagai pusat sumber belajar dan informasi yang mendukung kegiatan akademik sivitas akademika. Dengan koleksi yang terus berkembang dan layanan digital yang modern, kami berkomitmen memberikan akses informasi terbaik.',
            'image'       => [
                'src' => publicMedia('perpus-1.jpg', 'perpus'),
                'alt' => 'Perpustakaan Politeknik Caltex Riau'
            ],
            'data'        => [
                // Row 1 - Koleksi & Digital Resources
                [
                    'icon'      => 'fa-solid fa-book',
                    'value'     => '15000',
                    'label'     => 'Koleksi Buku',
                    'important' => true,
                    'delay'     => '0.1s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-tablet-screen-button',
                    'value'     => '5000',
                    'label'     => 'E-Book & E-Journal',
                    'important' => false,
                    'delay'     => '0.2s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-graduation-cap',
                    'value'     => '3500',
                    'label'     => 'Anggota Aktif',
                    'important' => false,
                    'delay'     => '0.9s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-clock',
                    'value'     => '12',
                    'label'     => 'Jam Operasional',
                    'important' => false,
                    'delay'     => '1.3s',
                    'counter'   => true,
                    'suffix'    => ' Jam/Hari'
                ],

                // Row 2 - Layanan & Aktivitas
                [
                    'icon'      => 'fa-solid fa-handshake',
                    'value'     => '8',
                    'label'     => 'Layanan Unggulan',
                    'important' => true,
                    'delay'     => '0.8s'
                ],
                [
                    'icon'      => 'fa-solid fa-book-open-reader',
                    'value'     => '25000',
                    'label'     => 'Peminjaman per Tahun',
                    'important' => false,
                    'delay'     => '1.1s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-users',
                    'value'     => '500',
                    'label'     => 'Pengunjung per Bulan',
                    'important' => false,
                    'delay'     => '1.2s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-wifi',
                    'value'     => '100',
                    'label'     => 'Area Berkoneksi WiFi',
                    'important' => false,
                    'delay'     => '1.6s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],

                // Row 3 - Repository & Digital Access
                [
                    'icon'      => 'fa-solid fa-file-lines',
                    'value'     => '2000',
                    'label'     => 'Karya Ilmiah di Repository',
                    'important' => true,
                    'delay'     => '0.3s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-database',
                    'value'     => '24/7',
                    'label'     => 'Akses OPAC Online',
                    'important' => false,
                    'delay'     => '0.5s'
                ],
                [
                    'icon'      => 'fa-solid fa-laptop',
                    'value'     => '10',
                    'label'     => 'Komputer Akses Publik',
                    'important' => false,
                    'delay'     => '0.4s',
                    'counter'   => true
                ],
                [
                    'icon'      => 'fa-solid fa-chair',
                    'value'     => '50',
                    'label'     => 'Kapasitas Tempat Duduk',
                    'important' => false,
                    'delay'     => '1.0s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],

                // Row 4 - Facilities & Services
                [
                    'icon'      => 'fa-solid fa-shield-halved',
                    'value'     => '100',
                    'label'     => 'Bebas Pustaka',
                    'important' => true,
                    'delay'     => '0.7s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],
                [
                    'icon'      => 'fa-solid fa-spell-check',
                    'value'     => '500',
                    'label'     => 'Cek Turnitin per Tahun',
                    'important' => false,
                    'delay'     => '0.6s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-building-columns',
                    'value'     => '300',
                    'label'     => 'Luas Perpustakaan',
                    'important' => false,
                    'delay'     => '1.4s',
                    'counter'   => true,
                    'suffix'    => ' m²'
                ],
                [
                    'icon'      => 'fa-solid fa-door-open',
                    'value'     => '6',
                    'label'     => 'Hari Buka per Minggu',
                    'important' => false,
                    'delay'     => '1.5s',
                    'counter'   => true
                ]
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
                    'description' => 'Katalog online perpustakaan untuk mencari dan menelusuri koleksi buku, jurnal, dan bahan pustaka lainnya.',
                    'url'    => 'https://opac.lib.pcr.ac.id/',
                    'icon'   => 'fa-solid fa-magnifying-glass',
                    'target' => '_blank'
                ],
                [
                    'name'   => 'Repository',
                    'description' => 'Repositori institusi untuk mengakses karya ilmiah mahasiswa dan dosen PCR.',
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
                'subtitle' => 'Fasilitas Perpustakaan',
                'title' => '<b>Fasilitas</b> yang Mendukung Kegiatan Belajar',
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
            'actions' => [
                'primary' => [
                    'text' => 'Lihat Galeri Fasilitas',
                    'url' => route('frontend.home'),
                    'class' => 'btn-default'
                ]
            ]
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
}

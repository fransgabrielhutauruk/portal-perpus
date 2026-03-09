<?php

namespace App\Services\Frontend;

use App\Models\Berita;

class BeritaService
{
    /**
     * Return berita content for the frontend Berita page.
     * Router method that delegates to index or show content based on slug.
     *
     * @param string|null $beritaSlug
     * @return array|null
     */
    public static function getContent(?string $beritaSlug = null): ?array
    {
        return $beritaSlug ? self::getShowContent($beritaSlug) : self::getIndexContent();
    }

    /**
     * Return index berita content (list of all news).
     * Fetches latest 12 published berita with formatted data.
     *
     * @return array
     */
    public static function getIndexContent(): array
    {
        $newest        = [];
        $newestRecords = self::getLatestBerita(12);

        foreach ($newestRecords as $index => $news) {
            $newest[] = [
                'title'     => $news->judul_berita,
                'excerpt'   => substr(strip_tags($news->isi_berita), 0, 200) . '...',
                'timestamp' => $news->tanggal_berita ? \Carbon\Carbon::parse($news->tanggal_berita)->diffForHumans() : '',
                'url'       => route('frontend.berita.show', ['beritaSlug' => $news->slug_berita]),
                'images'    => [
                    'src' => publicMedia($news->filename_berita, 'berita'),
                    'alt' => 'Cover ' . $news->judul_berita,
                ]
            ];
        }

        return [
            'header'        => 'Berita',
            'title'         => 'Berita Perpustakaan Politeknik Caltex Riau',
            'subtitle'      => '',
            'description'   => 'Dapatkan informasi terbaru seputar kegiatan dan perkembangan di Portal Perpus',
            'newest'        => $newest,
        ];
    }

    /**
     * Return single berita content with details and related news.
     * Fetches berita by slug with 5 latest news sidebar.
     *
     * @param string $beritaSlug
     * @return array|null
     */
    public static function getShowContent(string $beritaSlug): ?array
    {
        $selectedBerita = self::getBerita($beritaSlug);

        if (!$selectedBerita) {
            return null;
        }

        $latest        = [];
        $latestRecords = self::getLatestBerita(5);

        foreach ($latestRecords as $index => $value) {
            $latest[] = [
                'title'     => $value->judul_berita,
                'timestamp' => $value->tanggal_berita ? \Carbon\Carbon::parse($value->tanggal_berita)->diffForHumans() : '',
                'url'       => route('frontend.berita.show', ['beritaSlug' => $value->slug_berita]),
                'images'    => [
                    'src' => publicMedia($value->filename_berita, 'berita'),
                    'alt' => 'Cover ' . $value->judul_berita,
                ]
            ];
        }

        return [
            'header'        => $selectedBerita->judul_berita,
            'title'         => $selectedBerita->judul_berita,
            'url'           => route('frontend.berita.show', ['beritaSlug' => $selectedBerita->slug_berita]),
            'meta_desc'     => $selectedBerita->meta_desc_berita,
            'meta_keywords' => $selectedBerita->meta_keyword_berita,
            'latest_news'   => $latest,
            'content'       => [
                'id'            => $selectedBerita->berita_id,
                'title'         => $selectedBerita->judul_berita,
                'slug'          => $selectedBerita->slug_berita,
                'body'          => $selectedBerita->isi_berita,
                'excerpt'       => substr(strip_tags($selectedBerita->isi_berita), 0, 300) . '...',
                'date'          => $selectedBerita->tanggal_berita,
                'timestamp'     => $selectedBerita->tanggal_berita ? \Carbon\Carbon::parse($selectedBerita->tanggal_berita)->diffForHumans() : '',
                'author'        => $selectedBerita->author ? $selectedBerita->author->name : 'Administrator',
                'status'        => $selectedBerita->status_berita,
                'meta_description' => $selectedBerita->meta_desc_berita,
                'meta_keywords'    => $selectedBerita->meta_keyword_berita,
                'images'        => [
                    'src' => publicMedia($selectedBerita->filename_berita, 'berita'),
                    'alt' => 'Cover ' . $selectedBerita->judul_berita,
                ]
            ]
        ];
    }

    /**
     * Get default SEO metadata for Berita page.
     * Contains title, description, and keywords for search engines.
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => 'Berita Perpustakaan Politeknik Caltex Riau',
            'description' => 'Kumpulan berita terbaru Portal Perpus seputar kegiatan, informasi, dan perkembangan terkini.',
            'keywords'    => 'Berita, Portal Perpus, Informasi, Kegiatan, Perpustakaan'
        ];
    }

    /**
     * Get page configuration including background image and SEO structure.
     * Generates dynamic SEO data based on berita content or defaults.
     *
     * @param array|null $beritaContent
     * @return array
     */
    public static function getPageConfig(?array $beritaContent = null): array
    {
        $meta = self::getMetaData();
        $bg   = $beritaContent ? data_get($beritaContent, 'content.images.src') : publicMedia('berita-default.webp', 'berita');

        return [
            // Use publicMedia helper so media URL generation matches other services
            'background_image' => $bg,
            'seo'              => [
                'title'                      => $beritaContent ? data_get($beritaContent, 'title') : data_get($meta, 'title'),
                'description'                => $beritaContent ? data_get($beritaContent, 'meta_desc') : data_get($meta, 'description'),
                'keywords'                   => $beritaContent ? data_get($beritaContent, 'meta_keywords') : data_get($meta, 'keywords'),
                'canonical'                  => data_get($meta, 'canonical'),
                'og_image'                   => $bg,
                'og_type'                    => 'article',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Structured data for SEO (JSON-LD)
     *
     * @return array
     */
    public static function getStructuredData(string $bg): array
    {
        $metaData = self::getMetaData();

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Article',
            'headline'    => $metaData['title'],
            'description' => $metaData['description'],
            'author'      => [
                '@type' => 'Organization',
                'name'  => 'Portal Perpus'
            ],
            'publisher'   => [
                '@type' => 'Organization',
                'name'  => 'Portal Perpus',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => asset('theme/logo.png')
                ]
            ],
            'image'       => $bg,
            'url'         => url()->current()
        ];
    }

    /**
     * Breadcrumb structured data for SEO
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
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'Berita',
                    'item'     => route('frontend.berita.index')
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => data_get(self::getContent(), 'title'),
                    'item'     => url()->current()
                ]
            ]
        ];
    }

    /**
     * Get single berita record by slug.
     * Returns published berita with author relationship.
     *
     * @param string $beritaSlug
     * @return \App\Models\Berita|null
     */
    public static function getBerita(string $beritaSlug): ?\App\Models\Berita
    {
        try {
            return Berita::where('slug_berita', $beritaSlug)
                ->where('status_berita', 'published')
                ->with('author')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get latest published berita records.
     * Returns collection ordered by date descending.
     *
     * @param int $dataCount Number of records to fetch
     * @return \Illuminate\Support\Collection
     */
    public static function getLatestBerita(int $dataCount = 10): \Illuminate\Support\Collection
    {
        try {
            return Berita::where('status_berita', 'published')
                ->orderBy('tanggal_berita', 'DESC')
                ->limit($dataCount)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get berita data for landing page
     * 
     * @return array
     */
    public static function getBeritaForLanding(): array
    {
        $latestBerita = self::getLatestBerita(3);

        $highlighted = [];

        if ($latestBerita->count() > 0) {
            foreach ($latestBerita as $berita) {
                $highlighted[] = [
                    'title'     => $berita->judul_berita,
                    'timestamp' => $berita->tanggal_berita ? \Carbon\Carbon::parse($berita->tanggal_berita)->diffForHumans() : '',
                    'url'       => route('frontend.berita.show', ['beritaSlug' => $berita->slug_berita]),
                    'images'    => [
                        'src' => publicMedia($berita->filename_berita, 'berita'),
                        'alt' => 'Cover ' . $berita->judul_berita,
                    ]
                ];
            }
        }

        return [
            'content' => [
                'subtitle' => 'Berita & Informasi',
                'title' => '<b>Berita</b> Perpustakaan',
                'description' => 'Informasi terbaru seputar kegiatan, layanan, dan perkembangan perpustakaan PCR.',
            ],
            'highlighted' => $highlighted
        ];
    }
}

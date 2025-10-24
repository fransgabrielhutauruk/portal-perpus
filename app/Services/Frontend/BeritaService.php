<?php

namespace App\Services\Frontend;

use App\Models\Berita;

class BeritaService
{
    /**
     * Return berita content for the frontend Berita page.
     * In future this can fetch from DB or CMS.
     *
     * @return array|object
     */
    public static function getContent($beritaSlug = null)
    {
        return $beritaSlug ? self::getShowContent($beritaSlug) : self::getIndexContent();
    }

    /**
     * Return index berita content for the frontend Berita content.
     *
     * @return array|object
     */
    public static function getIndexContent()
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
     * Return show berita content for the frontend Berita Read content.
     *
     * @return array|object
     */
    public static function getShowContent($beritaSlug)
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
     * Optional metadata for the Berita page (SEO)
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title'       => 'Berita Perpustakaan Politeknik Caltex Riau',
            'description' => 'Kumpulan berita terbaru Portal Perpus seputar kegiatan, informasi, dan perkembangan terkini.',
            'keywords'    => 'Berita, Portal Perpus, Informasi, Kegiatan, Perpustakaan'
        ];
    }

    /**
     * Optional page config including background image and SEO structure
     *
     * @return array
     */
    public static function getPageConfig($beritaContent = null)
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
     * Get single berita by slug
     */
    public static function getBerita($beritaSlug)
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
     * Get latest berita
     */
    public static function getLatestBerita($dataCount = 10)
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


}

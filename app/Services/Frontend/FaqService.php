<?php

namespace App\Services\Frontend;

use App\Models\Faq;

class FaqService
{
    /**
     * Get all published FAQ for frontend display.
     * Returns collection ordered by creation date.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllFaq(): \Illuminate\Support\Collection
    {
        try {
            return Faq::select(['faq_id', 'pertanyaan', 'jawaban'])
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'ASC')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get default SEO metadata for FAQ page.
     * Contains title, description, and keywords for search engines.
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => 'Frequently Asked Questions (FAQ) - Perpustakaan PCR',
            'description' => 'Temukan jawaban untuk pertanyaan yang sering diajukan seputar layanan perpustakaan Politeknik Caltex Riau.',
            'keywords'    => 'FAQ, Pertanyaan, Jawaban, Perpustakaan, PCR, Bantuan',
        ];
    }

    /**
     * Get page configuration for FAQ including SEO structure.
     * Generates dynamic SEO data for the FAQ page.
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();

        return [
            'background_image' => publicMedia('perpus-11.webp', 'perpus'),
            'seo'              => [
                'title'                      => $meta['title'],
                'description'                => $meta['description'],
                'keywords'                   => $meta['keywords'],
                'og_image'                   => publicMedia('perpus-11.webp', 'perpus'),
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData(),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData(),
            ],
        ];
    }

    /**
     * Get index content for FAQ page with all FAQ items.
     * Fetches and formats FAQ data for display.
     *
     * @return array
     */
    public static function getIndexContent(): array
    {
        $faqList = self::getAllFaq();

        return [
            'page_title'       => 'FAQ',
            'page_subtitle'    => 'Frequently Asked <b>Questions</b>',
            'page_description' => 'Berikut adalah daftar pertanyaan yang sering diajukan beserta jawabannya.',
            'faq_list'         => $faqList,
            'total_faq'        => $faqList->count(),
        ];
    }

    /**
     * Get structured data for SEO (JSON-LD).
     * Creates FAQPage schema for search engines.
     *
     * @return array
     */
    public static function getStructuredData(): array
    {
        $faqList = self::getAllFaq();
        $mainEntity = [];

        foreach ($faqList as $faq) {
            $mainEntity[] = [
                '@type'          => 'Question',
                'name'           => $faq->pertanyaan,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $faq->jawaban,
                ],
            ];
        }

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    /**
     * Get breadcrumb structured data for SEO.
     * Creates breadcrumb navigation schema.
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
                    'item'     => route('frontend.home'),
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'FAQ',
                    'item'     => route('frontend.faq.index'),
                ],
            ],
        ];
    }
}

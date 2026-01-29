<?php

namespace App\Services\Frontend;

use App\Models\Faq;

class FaqService
{
    /**
     * Get all FAQ for frontend display
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllFaq()
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
     * Get page configuration for FAQ
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        return [
            'title' => 'Frequently Asked Questions (FAQ)',
            'description' => 'Temukan jawaban untuk pertanyaan yang sering diajukan seputar layanan perpustakaan.',
            'breadcrumb' => [
                ['name' => 'Beranda', 'url' => route('frontend.home')],
                ['name' => 'FAQ', 'url' => '']
            ]
        ];
    }

    /**
     * Get index content for FAQ page
     *
     * @return array
     */
    public static function getIndexContent(): array
    {
        $faqList = self::getAllFaq();

        return [
            'page_title' => 'FAQ',
            'page_subtitle' => 'Frequently Asked <b>Questions</b>',
            'page_description' => 'Berikut adalah daftar pertanyaan yang sering diajukan beserta jawabannya.',
            'faq_list' => $faqList,
            'total_faq' => $faqList->count()
        ];
    }
}

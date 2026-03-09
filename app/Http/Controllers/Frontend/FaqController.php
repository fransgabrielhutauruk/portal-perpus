<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\FaqService;
use App\Services\Frontend\SafeDataService;

class FaqController extends Controller
{
    /**
     * Display FAQ index page
     */
    public function index()
    {
        $fallbacks = SafeDataService::getFaqFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => FaqService::getIndexContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => FaqService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.faq.index', compact('content', 'pageConfig'));
    }
}

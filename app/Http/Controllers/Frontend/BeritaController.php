<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Frontend\BeritaService;
use App\Services\Frontend\SafeDataService;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $fallbacks = SafeDataService::getBeritaFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => BeritaService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => BeritaService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.berita.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function show($beritaSlug)
    {
        $fallbacks = SafeDataService::getBeritaShowFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => BeritaService::getContent($beritaSlug),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => BeritaService::getPageConfig($content),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.berita.show', compact(
            'content',
            'pageConfig'
        ));
    }
}

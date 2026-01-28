<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BeritaService;
use App\Services\Frontend\LandingService;
use App\Services\Frontend\HeroService;
use App\Services\Frontend\SafeDataService;

class MainController extends Controller
{
    public function index()
    {
        $fallbacks  = SafeDataService::getLandingFallbacks();
        $pageConfig = SafeDataService::safeExecute(
            fn() => LandingService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        $content    = SafeDataService::safeExecute(
            fn() => LandingService::getContent(),
            $fallbacks->konten ?? null
        );

        $heroData = SafeDataService::safeExecute(
            fn() => HeroService::getHeroData(),
            $fallbacks->hero
        );

        $statisticsData = SafeDataService::safeExecute(
            fn() => LandingService::getFactsAndStatisticsCallout(),
            $fallbacks->statistics
        );

        $beritaData = SafeDataService::safeExecute(
            fn() => BeritaService::getBeritaForLanding(),
            $fallbacks->berita ?? []
        );

        $aksesKoleksiData = SafeDataService::safeExecute(
            fn() => LandingService::getAksesKoleksiData(),
            $fallbacks->akses_koleksi ?? []
        );

        $fasilitasData = SafeDataService::safeExecute(
            fn() => LandingService::getFasilitasData(),
            $fallbacks->fasilitas ?? []
        );

        $layananData = SafeDataService::safeExecute(
            fn() => LandingService::getLayananData(),
            $fallbacks->layanan ?? []
        );

        $panduanData = SafeDataService::safeExecute(
            fn() => LandingService::getPanduanData(),
            $fallbacks->panduan ?? []
        );

        return view('contents.frontend.pages.index', compact(
            'pageConfig',
            'content',
            'heroData',
            'statisticsData',
            'aksesKoleksiData',
            'fasilitasData',
            'layananData',
            'panduanData',
            'beritaData',
        ));
    }
}

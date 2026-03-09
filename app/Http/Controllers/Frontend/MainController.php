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
        $pageConfig = SafeDataService::safeExecute(
            fn() => LandingService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );
        $content = SafeDataService::safeExecute(
            fn() => LandingService::getContent(),
            null
        );

        $heroData = SafeDataService::safeExecute(
            fn() => HeroService::getHeroData(),
            null
        );

        $statisticsData = SafeDataService::safeExecute(
            fn() => LandingService::getFactsAndStatisticsCallout(),
            []
        );

        $beritaData = SafeDataService::safeExecute(
            fn() => BeritaService::getBeritaForLanding(),
            []
        );

        $aksesKoleksiData = SafeDataService::safeExecute(
            fn() => LandingService::getAksesKoleksiData(),
            []
        );

        $fasilitasData = SafeDataService::safeExecute(
            fn() => LandingService::getFasilitasData(),
            []
        );

        $layananData = SafeDataService::safeExecute(
            fn() => LandingService::getLayananData(),
            []
        );

        $panduanData = SafeDataService::safeExecute(
            fn() => LandingService::getPanduanData(),
            []
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

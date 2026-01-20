<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BeritaService;
use App\Services\Frontend\LandingService;
use App\Services\Academic\JurusanService;
use App\Services\Frontend\PMBService;
use App\Services\CampusLife\VirtualTourService;
use App\Services\Frontend\SDGService;
use App\Services\Frontend\PartnershipService;
use App\Services\Frontend\HeroService;
use App\Services\Frontend\SiteIdentityService;
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

        $jurusanData = SafeDataService::safeExecute(
            fn() => JurusanService::getJurusanCallout(),
            $fallbacks->jurusan_list
        );

        $pmbData = SafeDataService::safeExecute(
            fn() => PMBService::getPMBData(),
            $fallbacks->pmb_data
        );

        $virtualTourData = SafeDataService::safeExecute(
            fn() => VirtualTourService::getCallout(),
            $fallbacks->virtual_tour
        );

        $sdgData = SafeDataService::safeExecute(
            fn() => SDGService::getSDGData(),
            $fallbacks->sdg
        );

        $beritaData = SafeDataService::safeExecute(
            fn() => BeritaService::getBeritaForLanding(),
            $fallbacks->berita ?? []
        );

        $partnershipData = SafeDataService::safeExecute(
            fn() => PartnershipService::getPartnershipData(),
            $fallbacks->partnership
        );

        $aksesKoleksiData = SafeDataService::safeExecute(
            fn() => LandingService::getAksesKoleksiData(),
            $fallbacks->akses_koleksi ?? []
        );

        $fasilitasData = SafeDataService::safeExecute(
            fn() => LandingService::getFasilitasData(),
            $fallbacks->fasilitas ?? []
        );

        return view('contents.frontend.pages.index', compact(
            'pageConfig',
            'content',
            'heroData',
            'statisticsData',
            'aksesKoleksiData',
            'fasilitasData',
            'jurusanData',
            'pmbData',
            'virtualTourData',
            'sdgData',
            'beritaData',
            'partnershipData',
        ));
    }
}

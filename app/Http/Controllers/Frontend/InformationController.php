<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\InformationService;
use App\Services\Frontend\SafeDataService;

class InformationController extends Controller
{
    public function profilPerpustakaan()
    {
        $pageConfig = SafeDataService::safeExecute(
            fn() => InformationService::getPageConfig('profil'),
            SafeDataService::getPageConfigFallbacks()
        );

        $content = SafeDataService::safeExecute(
            fn() => InformationService::getProfilContent(),
            null
        );

        return view('contents.frontend.pages.about.profil', compact('pageConfig', 'content'));
    }

    public function pustakawan()
    {
        $pageConfig = SafeDataService::safeExecute(
            fn() => InformationService::getPageConfig('pustakawan'),
            SafeDataService::getPageConfigFallbacks()

        );

        $content = SafeDataService::safeExecute(
            fn() => InformationService::getPustakawanContent(),
            null
        );

        $pustakawanList = SafeDataService::safeExecute(
            fn() => InformationService::getPustakawanList(),
            collect([])
        );

        return view('contents.frontend.pages.about.pustakawan', compact('pageConfig', 'content', 'pustakawanList'));
    }

    public function jamBuka()
    {
        $pageConfig = SafeDataService::safeExecute(
            fn() => InformationService::getPageConfig('jam-buka'),
            SafeDataService::getPageConfigFallbacks()
        );

        $content = SafeDataService::safeExecute(
            fn() => InformationService::getJamBukaContent(),
            null
        );

        $jadwal = SafeDataService::safeExecute(
            fn() => InformationService::getJamBukaSchedule(),
            []
        );

        return view('contents.frontend.pages.about.jam-buka', compact('pageConfig', 'content', 'jadwal'));
    }
}

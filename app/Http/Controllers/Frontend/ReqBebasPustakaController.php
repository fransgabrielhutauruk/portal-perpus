<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqBebasPustakaService;
use App\Http\Requests\Frontend\SubmitBebasPustakaRequest;

class ReqBebasPustakaController extends Controller
{
    /**
     * Display the Bebas Pustaka form
     */
    public function index()
    {
        $fallbacks = [
            'header' => 'Bebas Pustaka',
            'title' => 'Pengajuan Bebas Pustaka',
            'description' => 'Layanan administrasi perpustakaan',
            'history' => []
        ];

        $content = SafeDataService::safeExecute(
            fn() => ReqBebasPustakaService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqBebasPustakaService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.bebas_pustaka.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submit(SubmitBebasPustakaRequest $request)
    {
        $result = ReqBebasPustakaService::submitUsulan($request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'status' => 'error'
            ], $result['status_code']);
        }

        return response()->json([
            'message' => $result['message'],
            'status' => 'success',
            'new_data' => $result['data']
        ], $result['status_code']);
    }
}

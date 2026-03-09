<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubmitUsulanModulRequest;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqModulService;

class ReqModulController extends Controller
{
    /**
     * Display the usulan modul form page
     */
    public function index()
    {
        $fallbacks = SafeDataService::getUsulanModulFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqModulService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqModulService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.usulan.form_usulan_modul', compact(
            'content',
            'pageConfig'
        ));
    }

    /**
     * Submit the usulan modul form
     *
     * @param SubmitUsulanModulRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitUsulanModul(SubmitUsulanModulRequest $request)
    {
        $file = $request->hasFile('file') ? $request->file('file') : null;
        
        $result = ReqModulService::submitUsulan($request->validated(), $file);

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


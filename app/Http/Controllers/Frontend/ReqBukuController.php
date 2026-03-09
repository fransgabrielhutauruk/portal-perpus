<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubmitUsulanBukuRequest;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqBukuService;

class ReqBukuController extends Controller
{
    /**
     * Display the usulan buku form page
     */
    public function index()
    {
        $fallbacks = SafeDataService::getUsulanBukuFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqBukuService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqBukuService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.usulan.form_usulan_buku', compact(
            'content',
            'pageConfig'
        ));
    }

    /**
     * Submit the usulan buku form
     *
     * @param SubmitUsulanBukuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitUsulan(SubmitUsulanBukuRequest $request)
    {
        $result = ReqBukuService::submitUsulan($request->validated());

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

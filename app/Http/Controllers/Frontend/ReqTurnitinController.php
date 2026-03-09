<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqTurnitinService;
use App\Http\Requests\Frontend\SubmitTurnitinRequest;

class ReqTurnitinController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getTurnitinFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqTurnitinService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqTurnitinService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.turnitin.form_cek_turnitin', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submitTurnitin(SubmitTurnitinRequest $request)
    {
        $file = $request->hasFile('file_dokumen') ? $request->file('file_dokumen') : null;
        
        $result = ReqTurnitinService::submitTurnitin($request->validated(), $file);

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

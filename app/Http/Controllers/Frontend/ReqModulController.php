<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\FrontendVerificationPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubmitUsulanModulRequest;
use App\Models\Dimension\DmPegawai;
use App\Services\Frontend\GoogleVerificationService;
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

        app(GoogleVerificationService::class)->clearVerificationSession();

        return response()->json([
            'message' => $result['message'],
            'status' => 'success',
            'new_data' => $result['data']
        ], $result['status_code']);
    }

    public function verifyIdentity(GoogleVerificationService $googleVerificationService)
    {
        $email = $googleVerificationService->resolveGoogleEmail();

        if (empty($email)) {
            return response()->json($googleVerificationService->loginRequiredResponsePayload(), 401);
        }

        $pegawai = DmPegawai::findByEmail($email);
        if (!$pegawai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak terdaftar. Silahkan hubungi administrator!',
            ], 404);
        }

        $verificationToken = $googleVerificationService->issueToken(
            $pegawai->email,
            FrontendVerificationPurpose::REQ_MODUL->value
        );

        $googleVerificationService->clearVerificationSession();

        return response()->json([
            'status' => 'success',
            'data' => [
                'identity_type' => 'pegawai',
                'nama' => $pegawai->nama,
                'email' => $pegawai->email,
                'inisial' => $pegawai->inisial,
                'nip' => $pegawai->nip,
                'homebase' => $pegawai->homebase,
                'verification_token' => $verificationToken,
            ],
        ]);
    }
}


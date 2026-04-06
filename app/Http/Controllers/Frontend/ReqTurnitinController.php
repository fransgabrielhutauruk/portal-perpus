<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\FrontendVerificationPurpose;
use App\Http\Controllers\Controller;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqTurnitinService;
use App\Http\Requests\Frontend\SubmitTurnitinRequest;
use App\Models\Dimension\DmPegawai;
use App\Services\Frontend\GoogleVerificationService;

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

        app(GoogleVerificationService::class)->clearVerificationSession();

        return response()->json([
            'message' => $result['message'],
            'status' => 'success',
            'new_data' => $result['data']
        ], $result['status_code']);
    }

    /**
     * Verify turnitin requester from dm_pegawai by Google email.
     */
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
            FrontendVerificationPurpose::REQ_TURNITIN->value
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

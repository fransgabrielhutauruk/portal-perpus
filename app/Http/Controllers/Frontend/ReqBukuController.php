<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\FrontendVerificationPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubmitUsulanBukuRequest;
use App\Models\Dimension\DmPegawai;
use App\Services\Frontend\GoogleVerificationService;
use App\Services\Frontend\MahasiswaService;
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

    public function submitUsulan(SubmitUsulanBukuRequest $request)
    {
        $result = ReqBukuService::submitUsulan($request->validated());

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

    public function verifyIdentity(MahasiswaService $mahasiswaService, GoogleVerificationService $googleVerificationService)
    {
        $email = $googleVerificationService->resolveGoogleEmail();

        if (empty($email)) {
            return response()->json($googleVerificationService->loginRequiredResponsePayload(), 401);
        }

        $pegawai = DmPegawai::findByEmail($email);
        if ($pegawai) {
            $verificationToken = $googleVerificationService->issueToken(
                $pegawai->email,
                FrontendVerificationPurpose::REQ_BUKU->value
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

        $mahasiswa = $mahasiswaService->findByEmail($email);
        if ($mahasiswa) {
            $verifiedEmail = $mahasiswa['email'] ?? $email;
            $verificationToken = $googleVerificationService->issueToken(
                $verifiedEmail,
                FrontendVerificationPurpose::REQ_BUKU->value
            );

            $googleVerificationService->clearVerificationSession();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'identity_type' => 'mahasiswa',
                    'nama' => $mahasiswa['nama'],
                    'email' => $verifiedEmail,
                    'nim' => $mahasiswa['nim'],
                    'prodi' => $mahasiswa['prodi'] ?? null,
                    'verification_token' => $verificationToken,
                ],
            ]);
        }


        return response()->json([
            'status' => 'error',
            'message' => 'Email tidak terdaftar. Silahkan hubungi administator!',
        ], 404);
    }
}

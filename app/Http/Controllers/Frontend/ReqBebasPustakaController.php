<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\FrontendVerificationPurpose;
use App\Http\Controllers\Controller;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqBebasPustakaService;
use App\Http\Requests\Frontend\SubmitBebasPustakaRequest;
use App\Services\Frontend\GoogleVerificationService;
use App\Services\Frontend\MahasiswaService;

class ReqBebasPustakaController extends Controller
{
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

        $mahasiswa = $mahasiswaService->findByEmail($email);
        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak terdaftar sebagai mahasiswa.',
            ], 404);
        }

        $verificationToken = $googleVerificationService->issueToken(
            $mahasiswa['email'] ?? $email,
            FrontendVerificationPurpose::REQ_BEBAS_PUSTAKA->value
        );

        $googleVerificationService->clearVerificationSession();

        return response()->json([
            'status' => 'success',
            'data' => [
                'identity_type' => 'mahasiswa',
                'nama' => $mahasiswa['nama'],
                'email' => $mahasiswa['email'] ?? $email,
                'nim' => $mahasiswa['nim'],
                'prodi' => $mahasiswa['prodi'] ?? null,
                'verification_token' => $verificationToken,
            ],
        ]);
    }
}

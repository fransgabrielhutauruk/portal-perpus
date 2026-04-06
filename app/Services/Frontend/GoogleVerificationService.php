<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class GoogleVerificationService
{
    public const TOKEN_TTL_SECONDS = 600;

    public function resolveGoogleEmail(): ?string
    {
        $email = Auth::user()?->email ?? session('verified_google_email');

        return is_string($email) && $email !== '' ? $email : null;
    }

    public function resolveVerifiedEmailForSubmit(?string $token, string $purpose): ?string
    {
        if (Auth::check()) {
            $email = Auth::user()?->email;

            return is_string($email) && $email !== '' ? $email : null;
        }

        return $this->resolveEmailFromToken($token, $purpose);
    }

    public function resolveEmailFromToken(?string $token, string $purpose): ?string
    {
        if (!is_string($token) || $token === '') {
            return null;
        }

        try {
            $decrypted = Crypt::decryptString($token);
            $payload = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);

            if (!is_array($payload) || ($payload['purpose'] ?? null) !== $purpose) {
                return null;
            }

            $issuedAt = (int) ($payload['iat'] ?? 0);
            if ($issuedAt <= 0 || (now()->timestamp - $issuedAt) > self::TOKEN_TTL_SECONDS) {
                return null;
            }

            $email = $payload['email'] ?? null;

            return is_string($email) && $email !== '' ? $email : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function issueToken(string $email, string $purpose): string
    {
        return Crypt::encryptString(json_encode([
            'email' => $email,
            'purpose' => $purpose,
            'iat' => now()->timestamp,
        ]));
    }

    public function clearVerificationSession(): void
    {
        session()->forget(['verified_google_email', 'verified_google_name']);
    }

    public function buildVerifyLoginUrl(?string $redirect = null): string
    {
        return route('login.google.verify', [
            'provider' => 'google',
            'redirect' => $redirect ?: url()->previous(),
        ]);
    }

    public function loginRequiredResponsePayload(?string $redirect = null): array
    {
        return [
            'status' => 'error',
            'message' => 'Silakan login menggunakan akun Google PCR terlebih dahulu.',
            'login_url' => $this->buildVerifyLoginUrl($redirect),
        ];
    }
}

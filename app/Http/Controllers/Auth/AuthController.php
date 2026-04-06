<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle($provider)
    {
        session()->forget(['verified_google_email', 'verified_google_name']);

        $redirectUrl = request()->query('redirect');
        if (!empty($redirectUrl)) {
            session(['url.intended' => $redirectUrl]);
        } else {
            session()->forget('url.intended');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Redirect the user to Google for verification flow.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogleVerify($provider)
    {
        $redirectUrl = request()->query('redirect');
        if (!empty($redirectUrl)) {
            session(['url.intended' => $redirectUrl]);
        } else {
            session()->forget('url.intended');
        }

        return Socialite::driver($provider)
            ->redirectUrl(route('login.google.verify.callback', ['provider' => $provider]))
            ->redirect();
    }

    /**
     * Handle the Google callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback($provider)
    {
        try {
            $googleUser = Socialite::driver($provider)->user();
            $user = User::where('email', $googleUser->getEmail())->first();
            if (!$user) {
                return $this->redirectAfterAuthFailure('Email tidak diizinkan untuk login.');
            }
            Auth::login($user, true);

            activity()
                ->causedBy($user)
                ->useLog('auth')
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'provider' => $provider,
                ])
                ->log('Login ke sistem');

            return redirect()->intended('/app/dashboard');
        } catch (\Exception $e) {
            return $this->redirectAfterAuthFailure('Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    /**
     * Handle the Google callback for verification flow.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleVerifyCallback($provider)
    {
        try {
            $googleUser = Socialite::driver($provider)
                ->redirectUrl(route('login.google.verify.callback', ['provider' => $provider]))
                ->user();

            session([
                'verified_google_email' => $googleUser->getEmail(),
                'verified_google_name' => $googleUser->getName(),
            ]);
            
            $intended = session('url.intended');
            if ($this->isRequestFormUrl($intended)) {
                return redirect()->to($intended);
            }

            return redirect()->route('frontend.home');
        } catch (\Exception $e) {
            return $this->redirectAfterAuthFailure('Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    protected function redirectAfterAuthFailure(string $message)
    {
        $intended = session('url.intended');

        if ($this->isRequestFormUrl($intended)) {
            return redirect()->to($intended)->with('error', $message);
        }

        return redirect()->route('frontend.error.unauthorized')
            ->with('error', $message);
    }

    protected function isRequestFormUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host && $host !== request()->getHost()) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';

        return str_starts_with($path, '/layanan/req-');
    }
}

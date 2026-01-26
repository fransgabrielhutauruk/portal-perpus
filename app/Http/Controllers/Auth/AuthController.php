<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle($provider)
    {
        return Socialite::driver($provider)->redirect();
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
                return redirect()->route('frontend.error.unauthorized')
                    ->with('error', 'Email tidak diizinkan untuk login.');
            }
            Auth::login($user, true);
            return redirect()->intended('/app/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('frontend.error.unauthorized')
                ->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }
}

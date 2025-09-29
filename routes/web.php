<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

include_once __DIR__ . "/web-frontend.php";

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('app')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        generalRoute(App\Http\Controllers\Admin\DashboardController::class, 'dashboard', 'app');

        // generalRoute(App\Http\Controllers\Admin\Konten\MainController::class, 'konten-main', 'konten');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenController::class, 'konten', 'app');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenMainController::class, 'konten-main', 'app');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenSlideController::class, 'konten-slide', 'app');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenPageController::class, 'konten-page', 'app');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenJurusanController::class, 'konten-jurusan', 'app');
        generalRoute(App\Http\Controllers\Admin\Konten\KontenProdiController::class, 'konten-prodi', 'app');

        generalRoute(App\Http\Controllers\Admin\PostController::class, 'post', 'app');
        generalRoute(App\Http\Controllers\Admin\AgendaController::class, 'agenda', 'app');
        generalRoute(App\Http\Controllers\Admin\TestiController::class, 'testi', 'app');

        generalRoute(App\Http\Controllers\Admin\MediaController::class, 'media', 'app', false);
        generalRoute(App\Http\Controllers\Admin\MasterController::class, 'master', 'app');

        generalRoute(App\Http\Controllers\Admin\UserController::class, 'user', 'app');

        // Dev-only: Keen Icons gallery to see icon class names quickly
        Route::get('icons', function () {
            // Restrict to debug mode to avoid exposing in production
            if (!config('app.debug')) {
                abort(403, 'Icon gallery is only available in debug mode.');
            }

            $cssFile = public_path('theme/plugins/global/plugins.bundle.css');
            if (!file_exists($cssFile)) {
                abort(500, 'Keen Icons CSS not found at '.$cssFile);
            }

            $css = @file_get_contents($cssFile) ?: '';

            // Extract icon names for each family
            $outline = [];
            $solid = [];
            $duotone = [];

            if ($css) {
                // Matches patterns like .ki-setting-3.ki-outline:before
                if (preg_match_all('/\.ki-([a-z0-9\-]+)\.ki-outline:before/', $css, $m)) {
                    $outline = array_values(array_unique($m[1]));
                    sort($outline);
                }

                // Matches patterns like .ki-setting-3.ki-solid:before
                if (preg_match_all('/\.ki-([a-z0-9\-]+)\.ki-solid:before/', $css, $m2)) {
                    $solid = array_values(array_unique($m2[1]));
                    sort($solid);
                }

                // Duotone icons are defined like .ki-setting-3 .path1:before
                if (preg_match_all('/\.ki-([a-z0-9\-]+)\s*\.path1:before/', $css, $m3)) {
                    $duotone = array_values(array_unique($m3[1]));
                    sort($duotone);
                }
            }

            // Provide minimal $pageData for layout/sidebar bindings
            $pageData = (object) [
                'activeMenu' => 'icons',
                'activeRoot' => 'dev',
                'title' => 'Keen Icons Gallery',
                'breadCrump' => [],
            ];

            return view('dev.icons', compact('outline', 'solid', 'duotone', 'pageData'));
        })->name('app.icons');
    });

// //temporary
// Route::get('/media/{id}', function ($id) {
//     return serveMedia(decid($id));
// })->name('media.show');
// Route::get('/media/thumb/{id}', function ($id) {
//     return serveMedia(decid($id), true);
// })->name('media.thumb');

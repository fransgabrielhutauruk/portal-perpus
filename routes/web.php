<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

include_once __DIR__ . "/web-frontend.php";

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// File download route (outside auth for symlink compatibility)
Route::get('/uploads/{path}', function ($path) {
    $filePath = storage_path('app/public/uploads/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

Route::prefix('app')
    ->middleware(['auth', 'role:' . implode('|', UserRole::getAllRoles())])
    ->group(function () {
        generalRoute(App\Http\Controllers\Admin\DashboardController::class, 'dashboard', 'app');
        generalRoute(App\Http\Controllers\Admin\BeritaController::class, 'berita', 'app');
        generalRoute(App\Http\Controllers\Admin\PanduanController::class, 'panduan', 'app');
        generalRoute(App\Http\Controllers\Admin\FaqController::class, 'faq', 'app');

        Route::middleware('role:' . UserRole::ADMIN->value)->group(function () {
            generalRoute(App\Http\Controllers\Admin\UserController::class, 'user', 'app');
            generalRoute(App\Http\Controllers\Admin\ReqBukuController::class, 'usulan', 'app');
            generalRoute(App\Http\Controllers\Admin\ReqModulController::class, 'usulan-modul', 'app');
            generalRoute(App\Http\Controllers\Admin\PeriodeController::class, 'periode', 'app');
            generalRoute(App\Http\Controllers\Admin\ProdiController::class, 'prodi', 'app');
            Route::post('/usulan/approve', [App\Http\Controllers\Admin\ReqBukuController::class, 'approve'])->name('app.usulan.approve');
            Route::post('/usulan/reject', [App\Http\Controllers\Admin\ReqBukuController::class, 'reject'])->name('app.usulan.reject');
            Route::post('/usulan-modul/approve', [App\Http\Controllers\Admin\ReqModulController::class, 'approve'])->name('app.usulan-modul.approve');
            Route::post('/usulan-modul/reject', [App\Http\Controllers\Admin\ReqModulController::class, 'reject'])->name('app.usulan-modul.reject');

            // Req Management Routes
            generalRoute(App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'req-bebas-pustaka', 'app');
            Route::post('/req-bebas-pustaka/approve', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'approve'])->name('app.req-bebas-pustaka.approve');
            Route::post('/req-bebas-pustaka/reject', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'reject'])->name('app.req-bebas-pustaka.reject');

            generalRoute(App\Http\Controllers\Admin\ReqTurnitinController::class, 'req-turnitin', 'app');
            Route::post('/req-turnitin/approve', [App\Http\Controllers\Admin\ReqTurnitinController::class, 'approve'])->name('app.req-turnitin.approve');
            Route::post('/req-turnitin/reject', [App\Http\Controllers\Admin\ReqTurnitinController::class, 'reject'])->name('app.req-turnitin.reject');
        });


        Route::get('icons', function () {
            if (!config('app.debug')) {
                abort(403, 'Icon gallery is only available in debug mode.');
            }

            $cssFile = public_path('theme/plugins/global/plugins.bundle.css');
            if (!file_exists($cssFile)) {
                abort(500, 'Keen Icons CSS not found at ' . $cssFile);
            }

            $css = @file_get_contents($cssFile) ?: '';

            $outline = [];
            $solid = [];
            $duotone = [];

            if ($css) {
                if (preg_match_all('/\.ki-([a-z0-9\-]+)\.ki-outline:before/', $css, $m)) {
                    $outline = array_values(array_unique($m[1]));
                    sort($outline);
                }

                if (preg_match_all('/\.ki-([a-z0-9\-]+)\.ki-solid:before/', $css, $m2)) {
                    $solid = array_values(array_unique($m2[1]));
                    sort($solid);
                }

                if (preg_match_all('/\.ki-([a-z0-9\-]+)\s*\.path1:before/', $css, $m3)) {
                    $duotone = array_values(array_unique($m3[1]));
                    sort($duotone);
                }
            }

            $pageData = (object) [
                'activeMenu' => 'icons',
                'activeRoot' => 'dev',
                'title' => 'Keen Icons Gallery',
                'breadCrump' => [],
            ];

            return view('dev.icons', compact('outline', 'solid', 'duotone', 'pageData'));
        })->name('app.icons');
    });

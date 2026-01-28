<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;

include_once __DIR__ . "/web-frontend.php";

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
        generalRoute(App\Http\Controllers\Admin\PeriodeController::class, 'periode', 'app');
        generalRoute(App\Http\Controllers\Admin\ReqBukuController::class, 'usulan', 'app');
        Route::post('/usulan/approve', [App\Http\Controllers\Admin\ReqBukuController::class, 'approve'])->name('app.usulan.approve');
        Route::post('/usulan/reject', [App\Http\Controllers\Admin\ReqBukuController::class, 'reject'])->name('app.usulan.reject');
        Route::post('/usulan/reset', [App\Http\Controllers\Admin\ReqBukuController::class, 'reset'])->name('app.usulan.reset');
        generalRoute(App\Http\Controllers\Admin\ReqModulController::class, 'usulan-modul', 'app');
        Route::post('/usulan-modul/approve', [App\Http\Controllers\Admin\ReqModulController::class, 'approve'])->name('app.usulan-modul.approve');
        Route::post('/usulan-modul/reject', [App\Http\Controllers\Admin\ReqModulController::class, 'reject'])->name('app.usulan-modul.reject');
        Route::post('/usulan-modul/reset', [App\Http\Controllers\Admin\ReqModulController::class, 'reset'])->name('app.usulan-modul.reset');
        generalRoute(App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'req-bebas-pustaka', 'app');
        Route::post('/req-bebas-pustaka/approve', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'approve'])->name('app.req-bebas-pustaka.approve');
        Route::post('/req-bebas-pustaka/reject', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'reject'])->name('app.req-bebas-pustaka.reject');
        Route::post('/req-bebas-pustaka/reset', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'reset'])->name('app.req-bebas-pustaka.reset');
        Route::get('/req-bebas-pustaka/download', [App\Http\Controllers\Admin\ReqBebasPustakaController::class, 'download'])->name('app.req-bebas-pustaka.download');
        generalRoute(App\Http\Controllers\Admin\ReqTurnitinController::class, 'req-turnitin', 'app');
        Route::post('/req-turnitin/approve', [App\Http\Controllers\Admin\ReqTurnitinController::class, 'approve'])->name('app.req-turnitin.approve');
        Route::post('/req-turnitin/reject', [App\Http\Controllers\Admin\ReqTurnitinController::class, 'reject'])->name('app.req-turnitin.reject');
        Route::post('/req-turnitin/reset', [App\Http\Controllers\Admin\ReqTurnitinController::class, 'reset'])->name('app.req-turnitin.reset');

        Route::middleware('role:' . UserRole::ADMIN->value)->group(function () {
            generalRoute(App\Http\Controllers\Admin\PustakawanController::class, 'pustakawan', 'app');
            generalRoute(App\Http\Controllers\Admin\ProdiController::class, 'prodi', 'app');
            generalRoute(App\Http\Controllers\Admin\UserController::class, 'user', 'app');
        });
    });

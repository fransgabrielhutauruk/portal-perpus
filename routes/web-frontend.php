<?php

use App\Http\Controllers\Frontend\DEV;
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\InformationController;
use App\Http\Controllers\Frontend\PanduanController;
use App\Http\Controllers\Frontend\ReqBebasPustakaController;
use App\Http\Controllers\Frontend\MainController;
use App\Http\Controllers\Frontend\ReqBukuController;
use App\Http\Controllers\Frontend\ReqModulController;
use App\Http\Controllers\Frontend\ReqTurnitinController;
use Illuminate\Support\Facades\Route;

Route::name('frontend.')->group(function () {
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'index')->name('home');
    });

    Route::prefix('/berita')->name('berita.')->controller(BeritaController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{beritaSlug}', 'show')->name('show');
    });

    Route::prefix('/panduan')->name('panduan.')->controller(PanduanController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{panduanId}', 'show')->name('show');
    });

    Route::prefix('/faq')->name('faq.')->controller(FaqController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('/informasi')->name('information.')->controller(InformationController::class)->group(function () {
        Route::get('/kontak', 'contact')->name('contact');
        Route::get('/faq', 'faq')->name('faq');
        Route::prefix('/toko')->name('shop.')->controller(InformationController::class)->group(function () {
            Route::get('/', 'shop')->name('index');
            Route::get('/{id}', 'shopDetail')->name('show');
        });
    });

    Route::prefix('/tentang-kami')->name('about.')->controller(InformationController::class)->group(function () {
        Route::get('/profil-perpustakaan', 'profilPerpustakaan')->name('profil');
        Route::get('/pustakawan', 'pustakawan')->name('pustakawan');
        Route::get('/jam-buka', 'jamBuka')->name('jam-buka');
    });

    Route::prefix('/layanan')->name('req.')->group(function () {
        Route::controller(ReqBukuController::class)->group(function () {
            Route::get('/req-buku', 'index')->name('buku');
            Route::post('/req-buku/send', 'submitUsulan')->name('buku.send');
        });
        Route::controller(ReqModulController::class)->group(function () {
            Route::get('/req-modul', 'index')->name('modul');
            Route::post('/req-modul/send', 'submitUsulanModul')->name('modul.send');
        });
        Route::controller(ReqBebasPustakaController::class)->group(function () {
            Route::get('/req-bebas-pustaka', 'index')->name('bebas-pustaka');
            Route::post('/req-bebas-pustaka/send', 'submit')->name('bebas-pustaka.send');
        });
        Route::controller(ReqTurnitinController::class)->group(function () {
            Route::get('/req-turnitin', 'index')->name('turnitin');
            Route::post('/req-turnitin/submit', 'submitTurnitin')->name('turnitin.send');
        });
    });

    Route::prefix('/dev')->name('dev.')->controller(DEV\MainController::class)->group(function () {
        Route::get('/changelog', 'changelog')->name('changelog');
    });
});

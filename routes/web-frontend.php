<?php

use App\Http\Controllers\Frontend\Academic\JurusanController;
use App\Http\Controllers\Frontend\Academic\LecturerController;
use App\Http\Controllers\Frontend\Academic\ProdiController;
use App\Http\Controllers\Frontend\DEV;
use App\Http\Controllers\Frontend\Academic\MainController as AcademicController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\CampusLifeController;
use App\Http\Controllers\Frontend\InformationController;
use App\Http\Controllers\Frontend\MainController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\PCRSquadController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ResearchController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\UsulanController;
use App\Http\Controllers\Frontend\UsulanModulController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/read/{numeric}/{slug}',
    function ($numeric, $slug) {
        return redirect()->route('frontend.articles.show', ['articlesSlug' => $slug], 301);
    }
)->where('numeric', '[0-9]+');

// Frontend Routes
Route::name('frontend.')->group(function () {
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'index')->name('home');
    });

    Route::prefix('/berita')->name('berita.')->controller(BeritaController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{beritaSlug}', 'show')->name('show');
    });

    Route::prefix('/informasi')->name('information.')->controller(InformationController::class)->group(function () {
        Route::get('/kontak', 'contact')->name('contact');
        
        Route::get('/faq', 'faq')->name('faq');

        Route::prefix('/toko')->name('shop.')->controller(InformationController::class)->group(function () {
            Route::get('/', 'shop')->name('index');
            Route::get('/{id}', 'shopDetail')->name('show');
        });
    });

     Route::name('usulan.')->controller(UsulanController::class)->group(function () {
        Route::get('/usulan-buku', 'index')->name('usulan-buku');
        Route::post('/usulan/send', 'submitUsulan')->name('sendUsulan');  
        Route::get('/usulan-modul', 'index_modul')->name('usulan-modul');
        Route::post('/usulan/send-modul', 'submitUsulanModul')->name('sendUsulanModul');         
       // Route::get('/faq', 'faq')->name('faq');
    });
    

    // Development Routes
    Route::prefix('/dev')->name('dev.')->controller(DEV\MainController::class)->group(function () {
        Route::get('/changelog', 'changelog')->name('changelog');
    });
});

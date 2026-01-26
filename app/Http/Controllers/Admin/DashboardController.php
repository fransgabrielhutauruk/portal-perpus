<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReqBuku;
use App\Models\ReqModul;
use App\Models\ReqBebasPustaka;
use App\Models\ReqTurnitin;
use App\Enums\StatusRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '';
        // $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url('')];
    }

    function index()
    {
        $this->title        = 'Dashboard';
        $this->activeMenu   = 'dashboard';
        $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url()->current()];

        // Statistik Cards
        $stats = [
            'totalRequests' => ReqBuku::where('status_req', StatusRequest::MENUNGGU->value)->count() + ReqModul::where('status_req', StatusRequest::MENUNGGU->value)->count() + ReqBebasPustaka::where('status_req', StatusRequest::MENUNGGU->value)->count() + ReqTurnitin::where('status_req', StatusRequest::MENUNGGU->value)->count(),
            'reqBuku' => ReqBuku::where('status_req', StatusRequest::MENUNGGU->value)->count(),
            'reqModul' => ReqModul::where('status_req', StatusRequest::MENUNGGU->value)->count(),
            'reqBebasPustaka' => ReqBebasPustaka::where('status_req', StatusRequest::MENUNGGU->value)->count(),
            'reqTurnitin' => ReqTurnitin::where('status_req', StatusRequest::MENUNGGU->value)->count(),
        ];

        $this->dataView([
            'stats' => $stats,
        ]);

        return $this->view('admin.dashboard');
    }

    public function show($param1 = '', $param2 = '')
    {
        abort(404, 'Halaman tidak ditemukan');
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */

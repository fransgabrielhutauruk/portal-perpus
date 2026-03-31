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
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show dashboard summary cards.
     */
    public function index(): View
    {
        $this->title = 'Dashboard';
        $this->activeMenu = 'dashboard';
        $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url()->current()];

        $reqBuku = $this->countPendingRequests(new ReqBuku());
        $reqModul = $this->countPendingRequests(new ReqModul());
        $reqBebasPustaka = $this->countPendingRequests(new ReqBebasPustaka());
        $reqTurnitin = $this->countPendingRequests(new ReqTurnitin());

        $stats = [
            'totalRequests' => $reqBuku + $reqModul + $reqBebasPustaka + $reqTurnitin,
            'reqBuku' => $reqBuku,
            'reqModul' => $reqModul,
            'reqBebasPustaka' => $reqBebasPustaka,
            'reqTurnitin' => $reqTurnitin,
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

    private function countPendingRequests(Model $model): int
    {
        return (int) $model->newQuery()
            ->where('status_req', StatusRequest::MENUNGGU->value)
            ->count();
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */

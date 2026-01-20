<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\PanduanService;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    /**
     * Display panduan index page
     */
    public function index()
    {
        $content = PanduanService::getIndexContent();
        $pageConfig = PanduanService::getPageConfig();

        return view('contents.frontend.pages.panduan.index', compact('content', 'pageConfig'));
    }

    /**
     * Display single panduan (view PDF)
     */
    public function show($panduanId)
    {
        $panduan = PanduanService::getPanduanById($panduanId);

        if (!$panduan) {
            abort(404, 'Panduan tidak ditemukan');
        }

        // If panduan has file, redirect to PDF view
        if ($panduan->file) {
            $filePath = public_path('uploads/panduan/' . $panduan->file);
            
            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $panduan->judul . '.pdf"'
                ]);
            }
        }

        abort(404, 'File panduan tidak ditemukan');
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ReqTurnitin;
use App\Enums\StatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqTurnitinService;

class ReqTurnitinController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getTurnitinFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqTurnitinService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqTurnitinService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.turnitin.form_cek_turnitin', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submitTurnitin(Request $request)
    {
        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'inisial_dosen' => 'required|string|max:10',
            'email_dosen' => 'required|email',
            'nip' => 'required|string',
            'prodi_id' => 'required|numeric',
            'jenis_dokumen' => 'required|in:skripsi,artikel',
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'keterangan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $filePath = $request->file('file_dokumen')->store('uploads/turnitin', 'public');

            $reqTurnitin = ReqTurnitin::create([
                'prodi_id' => $request->prodi_id,
                'nama_dosen' => $request->nama_dosen,
                'inisial_dosen' => $request->inisial_dosen,
                'email_dosen' => $request->email_dosen,
                'nip' => $request->nip,
                'jenis_dokumen' => $request->jenis_dokumen,
                'judul_dokumen' => $request->judul_dokumen,
                'file_dokumen' => $filePath,
                'keterangan' => $request->keterangan,
                'status_req' => StatusRequest::MENUNGGU->value,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pengajuan cek turnitin berhasil dikirim!',
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
}

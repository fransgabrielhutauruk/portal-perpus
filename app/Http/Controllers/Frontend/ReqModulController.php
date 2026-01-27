<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ReqModul;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqModulService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReqModulController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getUsulanModulFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqModulService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqModulService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.usulan.form_usulan_modul', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submitUsulanModul(Request $request)
    {
        // Check if periode is open
        $activePeriode = DB::table('mst_periode')
            ->where('jenis_periode', 'req_modul')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereNull('deleted_at')
            ->first();

        if (!$activePeriode) {
            return response()->json([
                'message' => 'Periode pengajuan modul sedang tidak dibuka. Silakan hubungi admin perpustakaan.',
                'status' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'inisial_dosen' => 'required|string|max:10',
            'email_dosen' => 'required|email',
            'nip' => 'required|string',
            'prodi_id' => 'required|numeric',
            'nama_mata_kuliah' => 'required|string|max:255',
            'judul_modul' => 'required|string|max:255',
            'penulis_modul' => 'required|string',
            'tahun_modul' => 'required|numeric|digits:4',
            'praktikum' => 'required|boolean',
            'jumlah_dibutuhkan' => 'nullable|numeric',
            'deskripsi_kebutuhan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $filePath = $request->hasFile('file')
                ? $request->file('file')->store('uploads/modul', 'public')
                : null;

            $usulanModul = ReqModul::create([
                'periode_id' => $activePeriode->periode_id,
                'prodi_id' => $request->prodi_id,
                'nama_dosen' => $request->nama_dosen,
                'inisial_dosen' => $request->inisial_dosen,
                'email_dosen' => $request->email_dosen,
                'nip' => $request->nip,
                'nama_mata_kuliah' => $request->nama_mata_kuliah,
                'judul_modul' => $request->judul_modul,
                'penulis_modul' => $request->penulis_modul,
                'tahun_modul' => $request->tahun_modul,
                'praktikum' => $request->praktikum,
                'jumlah_dibutuhkan' => $request->jumlah_dibutuhkan ?? 0,
                'deskripsi_kebutuhan' => $request->deskripsi_kebutuhan,
                'file' => $filePath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Request modul berhasil dikirim!',
                'status' => 'success',
                'new_data' => [
                    'judul_modul' => $usulanModul->judul_modul,
                    'nama_mata_kuliah' => $usulanModul->nama_mata_kuliah,
                    'nama_dosen' => $usulanModul->nama_dosen,
                    'inisial_dosen' => $usulanModul->inisial_dosen,
                    'praktikum' => $usulanModul->praktikum,
                    'status' => $usulanModul->status
                ]
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

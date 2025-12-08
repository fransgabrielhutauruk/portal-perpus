<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService; // Import SafeData
use App\Services\Frontend\UsulanService; 
use App\Services\Frontend\UsulanModulService;  // Import UsulanService
use App\Models\Usulan;
use App\Models\UsulanModul;
use Illuminate\Support\Facades\DB;

class UsulanController extends Controller
{
    /**
     * Display the Usulan form using SafeDataService pattern
     */
    public function index()
    {
        // 1. Get Fallbacks
        $fallbacks = SafeDataService::getUsulanBukuFallbacks();

        // 2. Get Content (Form data, lists, headers)
        $content = SafeDataService::safeExecute(
            fn() => UsulanService::getContent(),
            $fallbacks
        );   
        // 3. Get Page Config (SEO, Backgrounds)
        $pageConfig = SafeDataService::safeExecute(
            fn() => UsulanService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        // 4. Return View
        return view('contents.frontend.pages.usulan.form_usulan', compact(
            'content',
            'pageConfig'
        ));
    }

    /**
     * Handle the Form Submission (CRUD: Create)
     */
    public function submitUsulan(Request $request)
{
     $validated = $request->validate([
            'nama_req'       => 'required|string|max:255',
            'email_req'      => 'required|email',
            'prodi_id'       => 'required|numeric',
            'nim'            => 'nullable|required_without:nip',
            'nip'            => 'nullable|required_without:nim',
            'judul_buku'     => 'required|string|max:255',
            'penulis_buku'   => 'required|string',
            'tahun_terbit'   => 'required|numeric|digits:4',
            'estimasi_harga' => 'required|numeric',
            'alasan_usulan'  => 'required|string',
        ]);

    try {
        // 1. RE-CHECK: Find active period ID right before saving
        $activePeriode = DB::table('mst_periode')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->first();

        // SECURITY CHECK: If no period is found, block the submission
        if (!$activePeriode) {
            return redirect()->back()
                ->with('error', 'Maaf, periode pengusulan buku saat ini sedang ditutup.');
        }

        DB::beginTransaction();

        // 2. CREATE: Use the ID from the query above ($activePeriode->periode_id)
        $data = Usulan::create([
            'periode_id' => $activePeriode->periode_id,
            'prodi_id'      => $request->prodi_id,
            'nama_req'      => $request->nama_req,
            'email_req'     => $request->email_req,
            'nim'           => $request->nim,
            'nip'           => $request->nip,
            'judul_buku'    => $request->judul_buku,
            'penulis_buku'  => $request->penulis_buku,
            'tahun_terbit'  => $request->tahun_terbit,
            'penerbit_buku' => $request->penerbit_buku ?? '-',
            'jenis_buku'    => $request->jenis_buku ?? 'Umum',
            'bahasa_buku'   => $request->bahasa_buku ?? 'Indonesia',
            'estimasi_harga'=> $request->estimasi_harga,
            'link_pembelian'=> $request->link_pembelian,
            'alasan_usulan' => $request->alasan_usulan,
            'status_req'    => 0, 
        ]);

        DB::commit();
       return response()->json([
            'message' => 'Usulan buku berhasil dikirim!',
            'status'  => 'success',            
            'new_data' => [
                'judul_buku'   => $data->judul_buku,
                'penulis_buku' => $data->penulis_buku,
                'nama_req'     => $data->nama_req,
                'date_fmt'     => $data->created_at->format('d M Y'),
                'status_req'   => $data->status_req
            ]
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            'status'  => 'error'
        ], 500);
    }
    }

    public function index_modul()
    {
        // 1. Get Fallbacks
        $fallbacks = SafeDataService::getUsulanModulFallbacks();

        // 2. Get Content (Form data, lists, headers)
        $content = SafeDataService::safeExecute(
            fn() => UsulanModulService::getContent(),
            $fallbacks
        );



        // 3. Get Page Config (SEO, Backgrounds)
        $pageConfig = SafeDataService::safeExecute(
            fn() => UsulanModulService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        // 4. Return View
        return view('contents.frontend.pages.usulan.form_usulan_modul', compact(
            'content',
            'pageConfig'
        ));
    }

public function submitUsulanModul(Request $request)
    {
        // 1. Validation: Adapted for Req Modul fields
        $validated = $request->validate([
            'nama_dosen'       => 'required|string|max:255',
            'inisial_dosen'    => 'required|string|max:10',
            'email_dosen'      => 'required|email',
            'nip'              => 'required|numeric',
            'prodi_id'         => 'required|numeric',
            
            'nama_mata_kuliah' => 'required|string|max:255',
            'judul_modul'      => 'required|string|max:255',
            'penulis_modul'    => 'required|string',
            'tahun_modul'      => 'required|numeric|digits:4',
            
            'praktikum'     => 'required|boolean', // 0 or 1
            'jumlah_dibutuhkan'=> 'nullable|numeric',
            'deskripsi_kebutuhan' => 'nullable|string',
            
            // New: File Validation (Max 10MB, PDF/DOC)
            'file'             => 'nullable|file|mimes:pdf,doc,docx|max:10240', 
        ]);

        try {
            // 2. RE-CHECK: Find active period ID right before saving
            // We use loose date checking to ensure it catches the period found by the service
            $activePeriode = DB::table('mst_periode')
                ->whereDate('tanggal_selesai', '>=', now()) 
                ->orderBy('tanggal_selesai', 'desc')
                ->first();

            // SECURITY CHECK: If no period is found, block the submission
            if (!$activePeriode) {
                return response()->json([
                    'message' => 'Maaf, periode pengajuan modul saat ini sedang ditutup.',
                    'status'  => 'error'
                ], 422);
            }

            DB::beginTransaction();

            // 3. FILE UPLOAD HANDLING
            $filePath = null;
            if ($request->hasFile('file')) {
                // Stores in storage/app/public/uploads/modul
                $filePath = $request->file('file')->store('uploads/modul', 'public'); 
            }

            // 4. CREATE: Map request to UsulanModul model
            $data = UsulanModul::create([
                'mstperiode_id'    => $activePeriode->periode_id, // Note: Column is mstperiode_id
                'prodi_id'         => $request->prodi_id,
                
                'nama_dosen'       => $request->nama_dosen,
                'inisial_dosen'    => $request->inisial_dosen,
                'email_dosen'      => $request->email_dosen,
                'nip'              => $request->nip,
                
                'nama_mata_kuliah' => $request->nama_mata_kuliah,
                'judul_modul'      => $request->judul_modul,
                'penulis_modul'    => $request->penulis_modul,
                'tahun_modul'      => $request->tahun_modul,
                
                'praktikum'     => $request->praktikum,
                'jumlah_dibutuhkan'=> $request->jumlah_dibutuhkan ?? 0,
                'deskripsi_kebutuhan' => $request->deskripsi_kebutuhan,
                
                'file'             => $filePath, // Save the path
                'status'           => 'Pending', 
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Request modul berhasil dikirim!',
                'status'  => 'success',            
                'new_data' => [
                    // Return data needed for the JS History Table
                    'judul_modul'      => $data->judul_modul,
                    'nama_mata_kuliah' => $data->nama_mata_kuliah,
                    'nama_dosen'       => $data->nama_dosen,
                    'inisial_dosen'    => $data->inisial_dosen,
                    'praktikum'     => $data->praktikum,
                    'status'           => $data->status
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Delete uploaded file if DB transaction fails to prevent junk files
            if (isset($filePath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'status'  => 'error'
            ], 500);
        }
    }

}
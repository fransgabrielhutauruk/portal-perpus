<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService; // Import SafeData
use App\Services\Frontend\UsulanService;   // Import UsulanService
use App\Models\Usulan;
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
}
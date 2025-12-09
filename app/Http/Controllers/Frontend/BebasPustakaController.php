<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\BebasPustakaService;
use App\Models\BebasPustaka;
use Illuminate\Support\Facades\DB;

class BebasPustakaController extends Controller
{
    /**
     * Display the Bebas Pustaka form
     */
    public function index()
    {
        // 1. Get Fallbacks (You might need to define this in SafeDataService or pass empty array)
        $fallbacks = [
            'header' => 'Bebas Pustaka',
            'title' => 'Pengajuan Bebas Pustaka',
            'description' => 'Layanan administrasi perpustakaan',
            'history' => []
        ];

        // 2. Get Content (Form data, lists, headers)
        $content = SafeDataService::safeExecute(
            fn() => BebasPustakaService::getContent(),
            $fallbacks
        );        
        // 3. Get Page Config (SEO, Backgrounds)
        $pageConfig = SafeDataService::safeExecute(
            fn() => BebasPustakaService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        // 4. Return View
        return view('contents.frontend.pages.bebas_pustaka.index', compact(
            'content',
            'pageConfig'
        ));
    }

    /**
     * Handle the Form Submission
     */
    public function submit(Request $request)
    {
        // 1. Validation: Matches form fields for Bebas Pustaka
        $validated = $request->validate([
            'nama_mahasiswa'  => 'required|string|max:255',
            'email_mahasiswa' => 'required|email',
            'nim'             => 'required|numeric',
            'prodi_id'        => 'required|numeric',
        ]);

        try {
            // Note: Unlike books, Bebas Pustaka usually doesn't have a strict "Period" 
            // unless tied to graduation. If you need it, uncomment the logic below.
            
            /*
            $activePeriode = DB::table('mst_periode')
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->first();

            if (!$activePeriode) {
                return response()->json([
                    'message' => 'Maaf, layanan bebas pustaka sedang ditutup.',
                    'status'  => 'error'
                ], 422);
            }
            */

            DB::beginTransaction();

            // 2. CREATE: Map request to BebasPustaka model
            $data = BebasPustaka::create([
                'nama_mahasiswa'  => $request->nama_mahasiswa,
                'email_mahasiswa' => $request->email_mahasiswa,
                'nim'             => $request->nim,
                'prodi_id'        => $request->prodi_id,
                
                // Defaults
                'is_syarat_terpenuhi' => false, // Will be checked by Admin
                'status'              => 'Pending',
                'catatan_admin'       => null,
                'file_hasil_bebas_pustaka' => null // Admin will upload this later
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pengajuan Bebas Pustaka berhasil dikirim!',
                'status'  => 'success',            
                'new_data' => [
                    // Return data needed for the JS History Table
                    'nama_mahasiswa' => $data->nama_mahasiswa,
                    'nim'            => $data->nim,
                    'date_fmt'       => $data->created_at->format('d M Y'),
                    'status'         => $data->status
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
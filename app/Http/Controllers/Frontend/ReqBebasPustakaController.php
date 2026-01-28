<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\StatusRequest;
use Illuminate\Http\Request;
use App\Models\ReqBebasPustaka;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqBebasPustakaService;

class ReqBebasPustakaController extends Controller
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
            fn() => ReqBebasPustakaService::getContent(),
            $fallbacks
        );
        // 3. Get Page Config (SEO, Backgrounds)
        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqBebasPustakaService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        // 4. Return View
        return view('contents.frontend.pages.bebas_pustaka.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submit(Request $request)
    {
        // Check if periode is open
        $activePeriode = DB::table('mst_periode')
            ->where('jenis_periode', 'req_bebas_pustaka')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereNull('deleted_at')
            ->first();

        Log::error('Active Periode:', (array) $activePeriode);
        if (!$activePeriode) {
            return response()->json([
                'message' => 'Periode pengajuan bebas pustaka sedang tidak dibuka. Silakan hubungi admin perpustakaan.',
                'status' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'nama_mahasiswa'  => 'required|string|max:255',
            'email_mahasiswa' => 'required|email',
            'nim'             => 'required|string',
            'prodi_id'        => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $data = ReqBebasPustaka::create([
                'periode_id'      => $activePeriode->periode_id,
                'nama_mahasiswa'  => $request->nama_mahasiswa,
                'email_mahasiswa' => $request->email_mahasiswa,
                'nim'             => $request->nim,
                'prodi_id'        => $request->prodi_id,

                'is_syarat_terpenuhi'      => false,
                'status_req'               => StatusRequest::MENUNGGU->value,
                'catatan_admin'            => null,
                'file_hasil_bebas_pustaka' => null
            ]);

            DB::commit();

            $data->load('prodi');

            return response()->json([
                'message' => 'Pengajuan Surat Bebas Pustaka berhasil dikirim!',
                'status'  => 'success',
                'new_data' => [
                    'nama_mahasiswa' => $data->nama_mahasiswa,
                    'nim'            => $data->nim,
                    'prodi_nama'     => $data->prodi->nama_prodi ?? '-',
                    'date_fmt'       => tanggal($data->created_at, ' '),
                    'status_req'     => $data->status_req
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

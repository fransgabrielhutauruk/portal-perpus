<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ReqBuku;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ReqBukuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReqBukuController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getUsulanBukuFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ReqBukuService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ReqBukuService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.usulan.form_usulan_buku', compact(
            'content',
            'pageConfig'
        ));
    }

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
            'penerbit'       => 'nullable|array',
            'penerbit.*'     => 'nullable|string',
            'jenis_buku'     => 'required|array|min:1',
            'jenis_buku.*'   => 'string',
            'bahasa_buku'    => 'required|in:indonesia,inggris',
            'estimasi_harga' => 'nullable|numeric',
            'link_pembelian' => 'required',
            'alasan_usulan'  => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $penerbitList = $request->penerbit ? implode(', ', array_filter($request->penerbit)) : '-';
            $jenisBukuList = implode(', ', $request->jenis_buku);
            $bahasaBuku = $request->bahasa_buku === 'inggris' ? 'Inggris' : 'Indonesia';

            $usulan = ReqBuku::create([
                'prodi_id' => $request->prodi_id,
                'nama_req' => $request->nama_req,
                'email_req' => $request->email_req,
                'nim' => $request->nim,
                'nip' => $request->nip,
                'judul_buku' => $request->judul_buku,
                'penulis_buku' => $request->penulis_buku,
                'tahun_terbit' => $request->tahun_terbit,
                'penerbit_buku' => $penerbitList,
                'jenis_buku' => $jenisBukuList,
                'bahasa_buku' => $bahasaBuku,
                'estimasi_harga' => $request->estimasi_harga,
                'link_pembelian' => $request->link_pembelian,
                'alasan_usulan' => $request->alasan_usulan,
                'status_req' => 0,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Usulan buku berhasil dikirim!',
                'status' => 'success',
                'new_data' => [
                    'judul_buku' => $usulan->judul_buku,
                    'penulis_buku' => $usulan->penulis_buku,
                    'nama_req' => $usulan->nama_req,
                    'date_fmt' => $usulan->created_at->format('d M Y'),
                    'status_req' => $usulan->status_req
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
}

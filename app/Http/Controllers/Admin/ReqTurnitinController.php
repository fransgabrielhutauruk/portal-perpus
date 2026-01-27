<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
use App\Models\ReqTurnitin;
use App\Jobs\SendTurnitinEmail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class ReqTurnitinController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Cek Turnitin';
        $this->activeMenu = 'req-turnitin';
        $this->breadCrump[] = ['title' => 'Request Cek Turnitin', 'link' => url()->current()];

        $roles = Role::all();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.req-turnitin.data') . '/list')->columns([
            Column::make(['title' => 'No', 'data' => 'no']),
            Column::make(['title' => 'Nama Dosen', 'data' => 'nama_dosen']),
            Column::make(['title' => 'NIP', 'data' => 'nip']),
            Column::make(['title' => 'Judul Dokumen', 'data' => 'judul_dokumen']),
            Column::make(['title' => 'Jenis', 'data' => 'jenis_dokumen']),
            Column::make(['title' => 'Status', 'data' => 'status_req']),
            Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'roles' => $roles
        ]);

        return $this->view('admin.req.turnitin');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(ReqTurnitin::getDataDetail($filter, get: true))->toArray();
            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['nama_dosen'] = $value['nama_dosen'] ?? '-';
                $dt['nip'] = $value['nip'] ?? '-';
                $dt['judul_dokumen'] = $value['judul_dokumen'] ?? '-';
                $dt['jenis_dokumen'] = $value['jenis_dokumen'] ?? '-';

                $dt['status_req'] = ReqTurnitin::getStatusBadge($value['status_req'] ?? null);

                $turnitin = ReqTurnitin::find($value['reqturnitin_id']);
                $id = $value['reqturnitin_id'];

                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];

                if ($turnitin && $turnitin->status_req == StatusRequest::MENUNGGU->value) {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]],
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                } else {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                }

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);
                $resp[] = $dt;
            }

            $data['data'] = $resp;

            return response()->json($data);
        } else if ($param1 = 'detail') {
            validate_and_response([
                'reqturnitin_id' => ['Parameter data', 'required'],
            ]);
            $currData = ReqTurnitin::with('prodi')->findOrFail($req->input('reqturnitin_id'));

            $userData = $currData->toArray();
            $userData['prodi_nama'] = $currData->prodi->nama_prodi ?? '-';

            // Add file URL to response
            if ($currData->file_dokumen) {
                $userData['file_url'] = asset($currData->file_dokumen);
            }

            // Add result file URL if exists
            if ($currData->file_hasil_turnitin) {
                $userData['file_hasil_url'] = asset('uploads/' . $currData->file_hasil_turnitin);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $userData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function approve(Request $req): JsonResponse
    {
        validate_and_response([
            'reqturnitin_id' => ['ID Request Turnitin', 'required'],
            'file_hasil' => ['File Hasil Turnitin', 'required|file|mimes:pdf,doc,docx|max:10240'],
        ]);

        $turnitin = ReqTurnitin::with('prodi')->findOrFail($req->input('reqturnitin_id'));

        /* ===============================
           1. UPLOAD FILE HASIL
        =============================== */
        $file = $req->file('file_hasil');
        $fileName = 'turnitin_' . $turnitin->nip . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('turnitin', $fileName, 'public');

        /* ===============================
           2. UPDATE DATABASE
        =============================== */
        $turnitin->file_hasil_turnitin = $filePath;
        $turnitin->status_req = StatusRequest::DISETUJUI->value;
        $turnitin->save();

        /* ===============================
           3. KIRIM EMAIL VIA QUEUE (BACKGROUND)
        =============================== */
        $dosenData = [
            'nama' => $turnitin->nama_dosen,
            'nip' => $turnitin->nip,
            'email' => $turnitin->email_dosen,
            'judul_dokumen' => $turnitin->judul_dokumen,
        ];

        $fullFilePath = storage_path('app/public/' . $filePath);
        SendTurnitinEmail::dispatch($dosenData, $fullFilePath);

        return response()->json([
            'status' => true,
            'message' => 'Request cek plagiarisme telah disetujui. Email sedang dikirim ke dosen.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqturnitin_id' => ['ID Request Turnitin', 'required'],
            'catatan_admin' => ['Alasan Penolakan', 'required'],
        ]);

        $turnitin = ReqTurnitin::findOrFail($req->input('reqturnitin_id'));
        $turnitin->status_req = StatusRequest::DITOLAK->value;
        $turnitin->catatan_admin = $req->input('catatan_admin') ?? '-';
        $turnitin->save();

        return response()->json([
            'status' => true,
            'message' => 'Request cek turnitin telah ditolak.'
        ]);
    }

    public function reset(Request $req): JsonResponse
    {
        validate_and_response([
            'reqturnitin_id' => ['ID Request Turnitin', 'required'],
        ]);

        $turnitin = ReqTurnitin::findOrFail($req->input('reqturnitin_id'));
        
        // Reset status dan field terkait
        $turnitin->status_req = StatusRequest::MENUNGGU->value;
        $turnitin->catatan_admin = null;
        $turnitin->file_hasil_turnitin = null;
        $turnitin->save();

        return response()->json([
            'status' => true,
            'message' => 'Status request telah direset ke menunggu.'
        ]);
    }

    public function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $id = $req->input('id');

            $currData = ReqTurnitin::where('reqturnitin_id', $id)->firstOrFail();

            DB::beginTransaction();
            try {
                $currData->delete();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Hapus data gagal, ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

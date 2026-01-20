<?php

namespace App\Http\Controllers\admin;

use App\Enums\StatusRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use App\Models\ReqModul;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Blade;

class ReqModulController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Modul';
        $this->activeMenu = 'usulan-modul';
        $this->breadCrump[] = ['title' => 'Request Modul', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)
            ->ajax(route('app.usulan-modul.data') . '/list')
            ->columns([
                Column::make(['title' => 'No', 'data' => 'no']),
                Column::make(['title' => 'Judul Modul', 'data' => 'judul_modul']),
                Column::make(['title' => 'Matkul', 'data' => 'nama_mata_kuliah']),
                Column::make(['title' => 'Dosen', 'data' => 'nama_dosen']),
                Column::make(['title' => 'Jenis', 'data' => 'jenis_modul']),
                Column::make(['title' => 'Jml', 'data' => 'jumlah_dibutuhkan']),
                Column::make(['title' => 'Status', 'data' => 'status_req']),
                Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
            ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        return $this->view('admin.usulan.list_modul');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(ReqModul::getDataDetail($filter, get: true))->toArray();
            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']               = ++$start;
                $dt['nama_dosen']       = $value['nama_dosen']       ?? '-';
                $dt['judul_modul']      = $value['judul_modul']      ?? '-';
                $dt['nama_mata_kuliah'] = $value['nama_mata_kuliah'] ?? '-';
                $dt['jumlah_dibutuhkan'] = $value['jumlah_dibutuhkan'] ?? '0';
                $dt['status_req'] = ReqModul::getStatusBadge($value['status_req'] ?? null);

                $isPraktikum = $value['praktikum'] == 1;
                $dt['jenis_modul'] = $isPraktikum
                    ? '<span class="badge badge-info">Praktikum</span>'
                    : '<span class="badge badge-secondary">Teori</span>';

                $id = $value['reqmodul_id'];

                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];

                if ($value['status_req'] == 0) {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]], // distinct attr
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]],   // distinct attr
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],  // distinct attr
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
        } else if ($param1 == 'detail') {
            validate_and_response([
                'reqmodul_id' => ['Parameter data', 'required'],
            ]);
            $currData = ReqModul::with('prodi')->findOrFail($req->input('reqmodul_id'));

            $userData = $currData->toArray();
            $userData['prodi_nama'] = $currData->prodi->nama_prodi ?? '-';

            if ($currData->file) {
                $userData['file_url'] = asset($currData->file);
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
            'reqmodul_id' => ['ID Request Modul', 'required'],
        ]);

        $usulan = ReqModul::findOrFail($req->input('reqmodul_id'));
        $usulan->status_req = StatusRequest::DISETUJUI->value;
        $usulan->save();

        return response()->json([
            'status' => true,
            'message' => 'Request modul telah disetujui.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqmodul_id' => ['ID Request Modul', 'required'],
        ]);

        $usulan = ReqModul::findOrFail($req->input('reqmodul_id'));
        $usulan->status_req = StatusRequest::DITOLAK->value;
        $usulan->catatan_admin = $req->input('catatan_admin') ?? '-';
        $usulan->save();

        return response()->json([
            'status' => true,
            'message' => 'Request modul telah ditolak.'
        ]);
    }

    public function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $id = $req->input('id');

            // Use reqmodul_id for query
            $currData = ReqModul::where('reqmodul_id', $id)->firstOrFail();

            DB::beginTransaction();
            try {
                // Optional: Delete file from storage if hard deleting
                // if ($currData->file) Storage::disk('public')->delete($currData->file);

                $currData->delete(); // Soft delete

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data modul berhasil dihapus'
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

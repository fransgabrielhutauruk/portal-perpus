<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\ReqModul;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class ReqModulController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Modul';
        $this->activeMenu = 'usulan-modul';
        $this->breadCrump[] = ['title' => 'Request Modul', 'link' => url()->current()];

        $periodeReqModul = Periode::query()
            ->select(['periode_id', 'nama_periode'])
            ->where('jenis_periode', Periode::TYPE_REQ_MODUL)
            ->whereNull('deleted_at')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)
            ->ajax(route('app.usulan-modul.data') . '/list')
            ->columns([
                Column::make(['title' => 'No', 'data' => 'no']),
                Column::make(['title' => 'Dikirim Pada', 'data' => 'dikirim_pada', 'orderable' => false]),
                Column::make(['title' => 'Judul Modul', 'data' => 'judul_modul']),
                Column::make(['title' => 'Matkul', 'data' => 'nama_mata_kuliah']),
                Column::make(['title' => 'Dosen', 'data' => 'nama_dosen']),
                Column::make(['title' => 'Jenis', 'data' => 'jenis_modul']),
                Column::make(['title' => 'Jumlah', 'data' => 'jumlah_dibutuhkan']),
                Column::make(['title' => 'Status', 'data' => 'status_req']),
                Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
            ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'periodeReqModul' => $periodeReqModul,
        ]);

        return $this->view('admin.usulan.list_modul');
    }

    public function data(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $filterPeriodeId = (string) $req->input('filter_periode_id', 'all');

            if ($filterPeriodeId !== 'all' && !ctype_digit($filterPeriodeId)) {
                $filterPeriodeId = 'all';
            }

            $data = DataTables::of(ReqModul::getDataDetail($filter, get: true, periodeId: $filterPeriodeId))->toArray();
            $start = (int) $req->input('start', 0);
            $resp = [];
            foreach ($data['data'] as $value) {
                $dt = [];
                $id = (int) ($value['reqmodul_id'] ?? 0);
                $statusReq = (int) ($value['status_req'] ?? StatusRequest::MENUNGGU->value);

                $dt['no']               = ++$start;
                $dt['dikirim_pada'] = $value['created_at'] ? date('d-m-Y H:i', strtotime($value['created_at'])) : '-';
                $dt['nama_dosen']       = $value['nama_dosen']       ?? '-';
                $dt['judul_modul']      = $value['judul_modul']      ?? '-';
                $dt['nama_mata_kuliah'] = $value['nama_mata_kuliah'] ?? '-';
                $dt['jumlah_dibutuhkan'] = $value['jumlah_dibutuhkan'] ?? '0';
                $dt['status_req'] = ReqModul::getStatusBadge($statusReq);

                $isPraktikum = (int) ($value['praktikum'] ?? 0) === 1;
                $dt['jenis_modul'] = $isPraktikum
                    ? '<span class="badge badge-secondary">Praktikum</span>'
                    : '<span class="badge badge-secondary">Teori</span>';

                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];

                if ($statusReq === StatusRequest::MENUNGGU->value) {
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
        } else if ($param1 == 'detail') {
            validate_and_response([
                'reqmodul_id' => ['Parameter data', 'required'],
            ]);

            $currData = ReqModul::with('prodi')->findOrFail((int) $req->input('reqmodul_id'));

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

    /**
     * Approve req modul.
     */
    public function approve(Request $req): JsonResponse
    {
        validate_and_response([
            'reqmodul_id' => ['ID Request Modul', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqModul::findOrFail((int) $req->input('reqmodul_id'));
            $usulan->status_req = StatusRequest::DISETUJUI->value;
            $usulan->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses persetujuan gagal: ' . $th->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Request modul telah disetujui.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqmodul_id' => ['ID Request Modul', 'required'],
            'catatan_admin' => ['Alasan Penolakan', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqModul::findOrFail((int) $req->input('reqmodul_id'));
            $usulan->status_req = StatusRequest::DITOLAK->value;
            $usulan->catatan_admin = clean_post('catatan_admin');
            $usulan->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses penolakan gagal: ' . $th->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Request modul telah ditolak.'
        ]);
    }

    public function reset(Request $req): JsonResponse
    {
        validate_and_response([
            'reqmodul_id' => ['ID Request Modul', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqModul::findOrFail((int) $req->input('reqmodul_id'));
            $usulan->status_req = StatusRequest::MENUNGGU->value;
            $usulan->catatan_admin = null;
            $usulan->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses reset status gagal: ' . $th->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Status request telah direset ke menunggu.'
        ]);
    }

    public function destroy(Request $req): JsonResponse
    {
        validate_and_response([
            'id' => ['Parameter data', 'required'],
        ]);

        $currData = ReqModul::where('reqmodul_id', (int) $req->input('id'))->firstOrFail();

        DB::beginTransaction();
        try {
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data request modul berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Hapus data gagal: ' . $th->getMessage());
        }
    }
}

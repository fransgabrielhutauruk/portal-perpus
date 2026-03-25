<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\ReqBuku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class ReqBukuController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Usulan Buku';
        $this->activeMenu = 'usulan';
        $this->breadCrump[] = ['title' => 'Usulan Buku', 'link' => url()->current()];

        $periodeReqBuku = Periode::query()
            ->select(['periode_id', 'nama_periode'])
            ->where('jenis_periode', Periode::TYPE_REQ_BUKU)
            ->whereNull('deleted_at')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.usulan.data') . '/list')->columns([
            Column::make(['title' => 'No', 'data' => 'no']),
            Column::make(['title' => 'Dikirim Pada', 'data' => 'dikirim_pada', 'orderable' => false]),
            Column::make(['title' => 'Judul Buku', 'data' => 'judul_buku']),
            Column::make(['title' => 'Tahun Terbit', 'data' => 'tahun_terbit']),
            Column::make(['title' => 'Nama', 'data' => 'nama_req']),
            Column::make(['title' => 'Email', 'data' => 'email_req']),
            Column::make(['title' => 'Status', 'data' => 'status_req']),
            Column::make(['title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'class' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'periodeReqBuku' => $periodeReqBuku,
        ]);

        return $this->view('admin.usulan.list');
    }

    public function data(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $filterPeriodeId = (string) $req->input('filter_periode_id', 'all');

            if ($filterPeriodeId !== 'all' && !ctype_digit($filterPeriodeId)) {
                $filterPeriodeId = 'all';
            }

            $data = DataTables::of(ReqBuku::getDataDetail($filter, get: true, periodeId: $filterPeriodeId))->toArray();
            $start = (int) $req->input('start', 0);
            $resp = [];
            foreach ($data['data'] as $value) {
                $dt = [];
                $id = (int) ($value['reqbuku_id'] ?? 0);
                $statusReq = (int) ($value['status_req'] ?? StatusRequest::MENUNGGU->value);

                $dt['no']       = ++$start;
                $dt['dikirim_pada'] = !empty($value['created_at'])
                    ? date('d-m-Y H:i', strtotime($value['created_at']))
                    : '-';
                $dt['nama_req']     = $value['nama_req']     ?? '-';
                $dt['email_req']    = $value['email_req']    ?? '-';
                $dt['judul_buku']   = $value['judul_buku']   ?? '-';
                $dt['tahun_terbit'] = $value['tahun_terbit'] ?? '-';
                $dt['status_req'] = ReqBuku::getStatusBadge($statusReq);

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
                'reqbuku_id' => ['Parameter data', 'required'],
            ]);

            $currData = ReqBuku::with(['prodi', 'periode'])->findOrFail((int) $req->input('reqbuku_id'));

            $userData = $currData->toArray();
            $userData['prodi_nama'] = $currData->prodi->nama_prodi ?? '-';
            $userData['jenis_periode'] = $currData->periode->jenis_periode ?? '-';

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
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqBuku::findOrFail((int) $req->input('reqbuku_id'));
            $usulan->status_req = StatusRequest::DISETUJUI->value;
            $usulan->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses persetujuan gagal: ' . $th->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Usulan buku telah disetujui.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
            'catatan_admin' => ['Alasan Penolakan', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqBuku::findOrFail((int) $req->input('reqbuku_id'));
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
            'message' => 'Usulan buku telah ditolak.'
        ]);
    }

    public function reset(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
        ]);

        DB::beginTransaction();
        try {
            $usulan = ReqBuku::findOrFail((int) $req->input('reqbuku_id'));
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
            'message' => 'Status usulan telah direset ke menunggu.'
        ]);
    }

    public function destroy(Request $req): JsonResponse
    {
        validate_and_response([
            'id' => ['Parameter data', 'required'],
        ]);

        $currData = ReqBuku::where('reqbuku_id', (int) $req->input('id'))->firstOrFail();

        DB::beginTransaction();
        try {
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data usulan berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Hapus data gagal: ' . $th->getMessage());
        }
    }
}

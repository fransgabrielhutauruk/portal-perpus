<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class PeriodeController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Periode Pengajuan Usulan';
        $this->activeMenu = 'periode';
        $this->breadCrump[] = ['title' => 'Periode', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.periode.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['title' => 'Nama', 'data' => 'nama_periode']),
            Column::make(['title' => 'Jenis Periode', 'data' => 'jenis_periode']),
            Column::make(['title' => 'Tanggal Mulai', 'data' => 'tanggal_mulai']),
            Column::make(['title' => 'Tanggal Selesai', 'data' => 'tanggal_selesai']),
            Column::make(['title' => 'Status', 'data' => 'status', 'orderable' => false]),
            Column::make(['title' => 'Aksi', 'data' => 'action']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'periodeTypes' => Periode::getTypeOptions(),
        ]);

        return $this->view('admin.periode.list');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(Periode::getDataDetail($filter, get: true))->toArray();

            $latestPeriodeIdByJenis = Periode::getLatestIdsByType();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['nama_periode'] = $value['nama_periode'] ?? '-';
                $dt['jenis_periode'] = Periode::getTypeLabel($value['jenis_periode'] ?? null);
                $dt['tanggal_mulai'] = tanggal($value['tanggal_mulai'], ' ') ?? '-';
                $dt['tanggal_selesai'] = tanggal($value['tanggal_selesai'], ' ') ?? '-';

                $tanggalMulai = $value['tanggal_mulai'] ?? null;
                $tanggalSelesai = $value['tanggal_selesai'] ?? null;
                $jenisPeriode = $value['jenis_periode'] ?? null;
                $periodeId = (int) ($value['periode_id'] ?? 0);
                $isLatestForJenis = $jenisPeriode
                    && isset($latestPeriodeIdByJenis[$jenisPeriode])
                    && $periodeId === (int) $latestPeriodeIdByJenis[$jenisPeriode];

                if ($tanggalMulai && $tanggalSelesai) {
                    if ($isLatestForJenis && periodeStatus($tanggalMulai, $tanggalSelesai, 'Y-m-d') === 'berlangsung') {
                        $dt['status'] = '<span class="badge badge-light-success">Dibuka</span>';
                    } else {
                        $dt['status'] = '<span class="badge badge-light-danger">Ditutup</span>';
                    }
                } else {
                    $dt['status'] = '<span class="badge badge-light-secondary">-</span>';
                }

                $id = encid($value['periode_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);
                $resp[] = $dt;
            }
            $data['data'] = $resp;

            return response()->json($data);
        } else if ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Periode::findOrFail(decid($req->input('id')));

            $periodeData = $currData->toArray();
            $periodeData['id'] = $req->input('id');

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $periodeData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            $jenisPeriodeRule = 'required|in:' . implode(',', Periode::getAllowedTypes());

            validate_and_response([
                'nama_periode' => ['Nama Periode', 'required|max:255'],
                'jenis_periode' => ['Jenis Periode', $jenisPeriodeRule],
                'tanggal_mulai' => ['Tanggal Mulai', 'required|date'],
                'tanggal_selesai' => ['Tanggal Selesai', 'required|date|after_or_equal:tanggal_mulai'],
            ]);

            $data = [
                'nama_periode' => clean_post('nama_periode'),
                'jenis_periode' => clean_post('jenis_periode'),
                'tanggal_mulai' => clean_post('tanggal_mulai'),
                'tanggal_selesai' => clean_post('tanggal_selesai'),
            ];

            DB::beginTransaction();
            try {
                $inserted = Periode::create($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Periode berhasil ditambahkan.',
                    'data' => ['id' => encid($inserted->periode_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Tambah data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Periode::findOrFail(decid($req->input('id')));

            DB::beginTransaction();
            try {
                $currData->delete();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Periode berhasil dihapus.'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Hapus data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function update(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            $jenisPeriodeRule = 'required|in:' . implode(',', Periode::getAllowedTypes());
            validate_and_response([
                'id' => ['Parameter data', 'required'],
                'nama_periode' => ['Nama Periode', 'required|max:255'],
                'jenis_periode' => ['Jenis Periode', $jenisPeriodeRule],
                'tanggal_mulai' => ['Tanggal Mulai', 'required|date'],
                'tanggal_selesai' => ['Tanggal Selesai', 'required|date|after_or_equal:tanggal_mulai'],
            ]);

            $id = decid($req->input('id'));
            $currData = Periode::findOrFail($id);

            $data = [
                'nama_periode' => clean_post('nama_periode'),
                'jenis_periode' => clean_post('jenis_periode'),
                'tanggal_mulai' => clean_post('tanggal_mulai'),
                'tanggal_selesai' => clean_post('tanggal_selesai'),
            ];

            DB::beginTransaction();
            try {
                $currData->update($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Periode berhasil diperbarui.',
                    'data' => ['id' => encid($currData->periode_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Update data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

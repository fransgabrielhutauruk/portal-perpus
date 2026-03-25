<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dimension\Prodi;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;

class ProdiController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Program Studi';
        $this->activeMenu = 'prodi';
        $this->breadCrump[] = ['title' => 'Program Studi', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.prodi.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['title' => 'Nama Program Studi', 'data' => 'nama_prodi']),
            Column::make(['title' => 'Alias Program Studi', 'data' => 'alias_prodi']),
            Column::make(['title' => 'Alias Jurusan', 'data' => 'alias_jurusan']),
            Column::make(['title' => 'Aksi', 'data' => 'action']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.prodi.list');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(Prodi::getDataDetail($filter, get: true))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']       = ++$start;
                $dt['nama_prodi']     = $value['nama_prodi'] ?? '-';
                $dt['alias_prodi']    = $value['alias_prodi'] ?? '-';
                $dt['alias_jurusan']    = $value['alias_jurusan'] ?? '-';

                $id = encid($value['prodi_id']);

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
        } elseif ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $id = $req->input('id');
            $currData = Prodi::findOrFail(decid($id))->makeHidden(Prodi::$exceptEdit);
            $currData->id = $id;

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $currData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req, $param1 = ''): JsonResponse
    {
        validate_and_response([
            'nama_prodi' => ['Nama Program Studi', 'required|max:255'],
            'alias_prodi' => ['Alias Program Studi', 'required|max:100'],
            'alias_jurusan' => ['Alias Jurusan', 'required|max:100'],
        ]);

        $data = [
            'nama_prodi' => clean_post('nama_prodi'),
            'alias_prodi' => clean_post('alias_prodi'),
            'alias_jurusan' => clean_post('alias_jurusan'),
        ];

        DB::beginTransaction();
        try {
            $inserted = Prodi::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->prodi_id)]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Tambah data gagal: ' . $th->getMessage());
        }
    }

    public function update(Request $req): JsonResponse
    {
        validate_and_response([
            'id' => ['Parameter data', 'required'],
            'nama_prodi' => ['Nama Program Studi', 'required|max:255'],
            'alias_prodi' => ['Alias Program Studi', 'required|max:100'],
            'alias_jurusan' => ['Alias Jurusan', 'required|max:100'],
        ]);

        $id = decid($req->input('id'));
        $currData = Prodi::findOrFail($id);

        $data = [
            'nama_prodi' => clean_post('nama_prodi'),
            'alias_prodi' => clean_post('alias_prodi'),
            'alias_jurusan' => clean_post('alias_jurusan'),
        ];

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil diperbarui.',
                'data' => ['id' => encid($id)]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Update data gagal: ' . $th->getMessage());
        }
    }

    public function destroy(Request $req): JsonResponse
    {
        validate_and_response([
            'id' => ['Parameter data', 'required'],
        ]);

        $currData = Prodi::findOrFail(decid($req->input('id')));

        DB::beginTransaction();
        try {
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Hapus data gagal: ' . $th->getMessage());
        }
    }
}

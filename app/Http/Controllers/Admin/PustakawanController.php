<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pustakawan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class PustakawanController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Pustakawan';
        $this->activeMenu = 'pustakawan';
        $this->breadCrump[] = ['title' => 'Pustakawan', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.pustakawan.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '10%', 'title' => 'Foto', 'data' => 'foto', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '', 'title' => 'Nama', 'data' => 'nama']),
            Column::make(['width' => '25%', 'title' => 'Email', 'data' => 'email']),
            Column::make(['width' => '15%', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.pustakawan.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title = 'Form Pustakawan';
            $this->activeMenu = 'pustakawan';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            if ($param2) {
                $filter['pustakawan_id'] = decid($param2);
                $get_pustakawan = Pustakawan::getDataDetail($filter, false)->first();

                if (!$get_pustakawan) {
                    abort(404, 'Pustakawan tidak ditemukan');
                }

                $dataPustakawan = [
                    'id' => encid($get_pustakawan->pustakawan_id),
                    'nama' => $get_pustakawan->nama,
                    'email' => $get_pustakawan->email,
                    'foto_path' => $get_pustakawan->foto ? asset('uploads/pustakawan/' . $get_pustakawan->foto) : null,
                ];
            } else {
                $dataPustakawan = [
                    'id' => '',
                    'nama' => '',
                    'email' => '',
                    'foto_path' => null,
                ];
            }

            $this->dataView([
                'dataPustakawan' => $dataPustakawan
            ]);

            return $this->view('admin.pustakawan.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req): JsonResponse
    {
        validate_and_response([
            'nama' => ['Nama', 'required|max:255'],
            'email' => ['Email', 'required|email|max:255|unique:pustakawan,email'],
        ]);

        $data = [
            'nama' => clean_post('nama'),
            'email' => clean_post('email'),
            'created_by' => Auth::id(),
        ];

        if ($req->hasFile('upload_foto')) {
            $do_upload = uploadMedia('upload_foto', 'pustakawan');
            if (!$do_upload['status'])
                abort(500, 'Tambah data gagal, ' . $do_upload['message']);

            $data['foto'] = $do_upload['data']['filename'];
        }

        DB::beginTransaction();
        try {
            $inserted = Pustakawan::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pustakawan berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->pustakawan_id)]
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
            'nama' => ['Nama', 'required|max:255'],
            'email' => ['Email', 'required|email|max:255|unique:pustakawan,email,' . decid($req->input('id')) . ',pustakawan_id'],
        ]);

        $id = decid($req->input('id'));
        $currData = Pustakawan::findOrFail($id);

        $data = [
            'nama' => clean_post('nama'),
            'email' => clean_post('email'),
            'updated_by' => Auth::id(),
        ];

        if ($req->hasFile('upload_foto')) {
            $do_upload = uploadMedia('upload_foto', 'pustakawan');
            if (!$do_upload['status'])
                abort(500, 'Update data gagal, ' . $do_upload['message']);

            $data['foto'] = $do_upload['data']['filename'];
        }

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pustakawan berhasil diperbarui.',
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

        $currData = Pustakawan::findOrFail(decid($req->input('id')));

        DB::beginTransaction();
        try {
            $currData->update(['deleted_by' => Auth::id()]);
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pustakawan berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Hapus data gagal: ' . $th->getMessage());
        }
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(Pustakawan::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                
                if ($value['foto']) {
                    $dt['foto'] = '<img src="' . asset('uploads/pustakawan/' . $value['foto']) . '" alt="' . $value['nama'] . '" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">';
                } else {
                    $dt['foto'] = '<div class="avatar avatar-md"><div class="avatar-initial rounded bg-label-secondary"><i class="bi bi-person fs-3"></i></div></div>';
                }

                $dt['nama'] = $value['nama'] ?? '-';
                $dt['email'] = $value['email'] ?? '-';

                $id = encid($value['pustakawan_id']);

                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.pustakawan.show', ['param1' => 'form', 'param2' => $id])],
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

            $currData = Pustakawan::findOrFail(decid($req->input('id')))->makeHidden(Pustakawan::$exceptEdit);
            $currData->id = $req->input('id');
            $currData->foto_path = $currData->foto ? asset('uploads/pustakawan/' . $currData->foto) : null;

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $currData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Panduan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class PanduanController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Panduan';
        $this->activeMenu = 'panduan';
        $this->breadCrump[] = ['title' => 'Panduan', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.panduan.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '', 'title' => 'Judul', 'data' => 'judul']),
            Column::make(['width' => '30%', 'title' => 'Deskripsi', 'data' => 'deskripsi']),
            Column::make(['width' => '15%', 'title' => 'File', 'data' => 'file', 'className' => 'text-center']),
            Column::make(['width' => '15%', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.panduan.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title = 'Form Panduan';
            $this->activeMenu = 'panduan';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            if ($param2) {
                $filter['panduan_id'] = decid($param2);
                $get_panduan = Panduan::getDataDetail($filter, false)->first();

                if (!$get_panduan) {
                    abort(404, 'Panduan tidak ditemukan');
                }

                $dataPanduan = [
                    'id' => encid($get_panduan->panduan_id),
                    'judul' => $get_panduan->judul,
                    'deskripsi' => $get_panduan->deskripsi,
                    'file_path' => $get_panduan->file ? asset('uploads/panduan/' . $get_panduan->file) : null,
                ];
            } else {
                $dataPanduan = [
                    'id' => '',
                    'judul' => '',
                    'deskripsi' => '',
                    'file_path' => null,
                ];
            }

            $this->dataView([
                'dataPanduan' => $dataPanduan
            ]);

            return $this->view('admin.panduan.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req): JsonResponse
    {
        validate_and_response([
            'judul' => ['Judul', 'required|max:255'],
            'deskripsi' => ['Deskripsi', 'nullable'],
        ]);

        $data = [
            'judul' => clean_post('judul'),
            'deskripsi' => clean_post('deskripsi'),
            'created_by' => Auth::id(),
        ];

        if ($req->hasFile('upload_file')) {
            $do_upload = uploadMedia('upload_file', 'panduan');
            if (!$do_upload['status'])
                abort(500, 'Tambah data gagal, ' . $do_upload['message']);

            $data['file'] = $do_upload['data']['filename'];
        }

        DB::beginTransaction();
        try {
            $inserted = Panduan::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Panduan berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->panduan_id)]
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
            'judul' => ['Judul', 'required|max:255'],
            'deskripsi' => ['Deskripsi', 'nullable'],
        ]);

        $id = decid($req->input('id'));
        $currData = Panduan::findOrFail($id);

        $data = [
            'judul' => clean_post('judul'),
            'deskripsi' => clean_post('deskripsi'),
            'updated_by' => Auth::id(),
        ];

        if ($req->hasFile('upload_file')) {
            $do_upload = uploadMedia('upload_file', 'panduan');
            if (!$do_upload['status'])
                abort(500, 'Update data gagal, ' . $do_upload['message']);

            $data['file'] = $do_upload['data']['filename'];
        }

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Panduan berhasil diperbarui.',
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

        $currData = Panduan::findOrFail(decid($req->input('id')));

        DB::beginTransaction();
        try {
            $currData->update(['deleted_by' => Auth::id()]);
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Panduan berhasil dihapus.'
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
            $data = DataTables::of(Panduan::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['judul'] = $value['judul'] ?? '-';
                $dt['deskripsi'] = $value['deskripsi'] ? (strlen($value['deskripsi']) > 100 ? substr($value['deskripsi'], 0, 100) . '...' : $value['deskripsi']) : '-';

                if ($value['file']) {
                    $dt['file'] = '<a href="' . asset('uploads/panduan/' . $value['file']) . '" target="_blank" class="btn btn-sm btn-light-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat File</a>';
                } else {
                    $dt['file'] = '<span class="badge badge-light-secondary">Tidak ada file</span>';
                }

                $id = encid($value['panduan_id']);

                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.panduan.show', ['param1' => 'form', 'param2' => $id])],
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

            $currData = Panduan::findOrFail(decid($req->input('id')))->makeHidden(Panduan::$exceptEdit);
            $currData->id = $req->input('id');
            $currData->file_path = $currData->file ? asset('uploads/panduan/' . $currData->file) : null;

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

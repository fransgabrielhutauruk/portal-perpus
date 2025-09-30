<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeRoot = 'manajemen_sistem';
        $this->breadCrump[] = ['title' => 'Manajemen Sistem', 'link' => url('#')];
    }

    public function index()
    {
        $this->title = 'Kelola Pengguna';
        $this->activeMenu = 'pengguna';
        $this->breadCrump[] = ['title' => 'Pengguna', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.user.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '45%', 'title' => 'Nama', 'data' => 'name']),
            Column::make(['width' => '50%', 'title' => 'Email', 'data' => 'email']),
            Column::make(['width' => '', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        return $this->view('admin.pengguna.list');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(User::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']       = ++$start;
                $dt['name']     = $value['name'] ?? '-';
                $dt['email']    = $value['email'] ?? '-';

                $id = encid($value['id']);

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
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'name' => ['Nama', 'required'],
                'email' => ['Email', 'required|email'],
            ]);

            $data['name'] = clean_post('name');
            $data['email'] = clean_post('email');
            $data['password'] = bcrypt(uniqid());

            $inserted = User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Pengguna berhasil ditambah.',
                'data' => ['id' => encid($inserted->id)]
            ]);
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

            $currData = User::findOrFail(decid($req->input('id')));

            $currData->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

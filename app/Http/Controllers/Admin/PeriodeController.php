<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Periode;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Blade;

class PeriodeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $this->title = 'Kelola Periode Pengajuan Usulan';
        $this->activeMenu = 'periode';
        $this->breadCrump[] = ['title' => 'Periode', 'link' => url()->current()];

        $roles = Role::all();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.periode.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['title' => 'Nama', 'data' => 'nama_periode']),
            Column::make(['title' => 'Jenis Periode', 'data' => 'jenis_periode']),
            Column::make(['title' => 'Tanggal Mulai', 'data' => 'tanggal_mulai']),            
            Column::make(['title' => 'Tanggal Selesai', 'data' => 'tanggal_selesai']),
            Column::make(['title' => 'Aksi', 'data' => 'action']),            
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'roles' => $roles
        ]);

        return $this->view('admin.periode.list');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(Periode::getDataDetail($filter, get: true))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']       = ++$start;
                $dt['nama_periode']     = $value['nama_periode'] ?? '-';
                $dt['jenis_periode']    = ucwords(str_replace('_', ' ', $value['jenis_periode'] ?? '-'));
                $dt['tanggal_mulai']    = tanggal($value['tanggal_mulai'], ' ') ?? '-';
                $dt['tanggal_selesai']    = tanggal($value['tanggal_selesai'], ' ') ?? '-';
                

                $Periode = Periode::find($value['periode_id']);

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
        } else if ($param1 = 'detail') {        
            $id_periode = decid($req->input('id'));
            
            /*
            validate_and_response([
                'periode_id' => [$id_periode, 'required'],
            ]);
            */
            $currData = Periode::findOrFail($id_periode);

            $PeriodeData = $currData->toArray();
          //  $PeriodeData['role'] = $currData->roles->value('name') ?? '';

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $PeriodeData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'nama_periode' => ['Nama', 'required'],
                'jenis_periode' => ['Jenis Periode', 'required|in:req_buku,req_modul,req_bebas_pustaka'],
                'tanggal_mulai' => ['tanggal_mulai', 'required|date'],
                'tanggal_selesai' => ['tanggal_selesai', 'required|date'],
            ]);
           
            $data['nama_periode'] = clean_post('nama_periode');
            $data['jenis_periode'] = clean_post('jenis_periode');
            $data['tanggal_mulai'] = clean_post('tanggal_mulai');
            $data['tanggal_selesai'] = clean_post('tanggal_selesai');
            $data['password'] = bcrypt(uniqid());
           // $role = clean_post('role');

            DB::beginTransaction();
            try {
                $inserted = Periode::create($data);

               // $inserted->assignRole($role);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Periode berhasil ditambah.',
                    'data' => ['periode_id' => encid($inserted->periode_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Tambah data gagal, ' . $th->getMessage());
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
            $id = $req->input('id');
            $currData = Periode::findOrFail(decid($id));

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

    public function update(Request $req, $param1 = ''): JsonResponse
    {        
        if ($param1 == '') {
            validate_and_response([
                'nama_periode' => ['nama_periode', 'required'],
                'jenis_periode' => ['Jenis Periode', 'required|in:req_buku,req_modul,req_bebas_pustaka'],
                'tanggal_mulai' => ['tanggal_mulai', 'required|date'],
                'tanggal_selesai' => ['tanggal_selesai', 'required|date'],
            ]);            
            $id = $req->input('periode_id');            
            $currData = Periode::findOrFail($id);            

            $data['nama_periode'] = $req->input('nama_periode');
            $data['jenis_periode'] = $req->input('jenis_periode');
            $data['tanggal_mulai'] = $req->input('tanggal_mulai');
            $data['tanggal_selesai'] = $req->input('tanggal_selesai');
           // $newRole = clean_post('role');

            DB::beginTransaction();
            try {
                $currData->update($data);

               // $currData->syncRoles([$newRole]);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Update data berhasil.',
                    'data' => ['periode_id' => $id]
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

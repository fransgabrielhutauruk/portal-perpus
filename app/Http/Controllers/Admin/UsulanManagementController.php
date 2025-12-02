<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usulan;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class UsulanManagementController extends Controller
{    
    public function index()
    {
        $this->title = 'Kelola Usulan Buku';
        $this->activeMenu = 'usulan';
        $this->breadCrump[] = ['title' => 'usulan', 'link' => url()->current()];

        $roles = Role::all();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.usulan.data') . '/list')->columns([
            Column::make(['title' => 'No', 'data' => 'no']),
            Column::make(['title' => 'Judul Buku', 'data' => 'judul_buku']),
            Column::make(['title' => 'Tahun Terbit', 'data' => 'tahun_terbit']),
            Column::make(['title' => 'Nama', 'data' => 'nama_req']),
            Column::make(['title' => 'Email', 'data' => 'email_req']),         
            Column::make(['title' => 'Status', 'data' => 'status_req']),       
            Column::make(['title' => 'Aksi', 'data' => 'action']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'roles' => $roles
        ]);

        return $this->view('admin.usulan.list');
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(Usulan::getDataDetail($filter, get: true))->toArray();            
            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']       = ++$start;
                $dt['nama_req']     = $value['nama_req']     ?? '-';
                $dt['email_req']    = $value['email_req']    ?? '-';
                $dt['judul_buku']   = $value['judul_buku']   ?? '-';
                $dt['tahun_terbit'] = $value['tahun_terbit'] ?? '-';
                $dt['status_req'] = UsulanManagementController::translateStatus($value['status_req']) ?? '-';

              //  $dt['email']    = $value['email'] ?? '-';

                $usulan = Usulan::find($value['reqbuku_id']);                                       
              // $dt['role'] = $user ? $user->roles()->value('name') : 'No Role';
                              
               // $id = encid($value['reqbuku_id']);
                $id = $value['reqbuku_id'];
                
                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];
                if  ($usulan && $usulan->status_req == '0'){
                    $dataAction['btn'] = [
                        
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]],
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]], 
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],                                             
                    ];
                    }
                else{
                    $dataAction['btn'] = [
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
                'reqbuku_id' => ['Paramater data', 'required'],
            ]);
            $currData = Usulan::findOrFail(decid($req->input('reqbuku_id')));

            $userData = $currData->toArray();
          //  $userData['role'] = $currData->roles->value('name') ?? '';

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $userData
            ]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function ManageApproval(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
        ]);
        
        $usulan = Usulan::findOrFail($req->input('reqbuku_id'));
        //dd($usulan);
        if ($req->input('alasan_reject')){
            $usulan->status_req = '2';
            $usulan->alasan_reject = $req->input('catatan_admin') ?? '-';
        } else {
            $usulan->status_req = '1'; 
        }
        $usulan->save();

        return response()->json([
            'status' => true,
            'message' => 'Usulan buku telah disetujui.'
        ]);
    }

    public function approve(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
        ]);
        
        $usulan = Usulan::findOrFail($req->input('reqbuku_id'));
        //dd($usulan);
        $usulan->status_req = '1'; // Disetujui
        $usulan->save();

        return response()->json([
            'status' => true,
            'message' => 'Usulan buku telah disetujui.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbuku_id' => ['ID Usulan Buku', 'required'],
        ]);

        $usulan = Usulan::findOrFail($req->input('reqbuku_id'));
        $usulan->status_req = '2'; // Ditolak
        $usulan->catatan_admin = $req->input('catatan_admin') ?? '-';
        
        $usulan->save();

        return response()->json([
            'status' => true,
            'message' => 'Usulan buku telah ditolak.'
        ]);
    }

    public function translateStatus($status)
    {
        switch ($status) {
            case '0':
                return 'Pending';
            case '1':
                return 'Disetujui';
            case '2':
                return 'Ditolak';
            default:
                return 'Unknown';
        }
    }
    public function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);            
            $id = $req->input('id');

            $currData = Usulan::where('reqbuku_id', $id)->firstOrFail();

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

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use App\Models\UsulanModul;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Blade;

class UsulanModulManagementController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Modul';
        $this->activeMenu = 'usulan-modul';
        $this->breadCrump[] = ['title' => 'Request Modul', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)
            ->ajax(route('app.usulan-modul.data') . '/list') // Ensure route exists
            ->columns([
                Column::make(['title' => 'No', 'data' => 'no']),
                Column::make(['title' => 'Judul Modul', 'data' => 'judul_modul']),
                Column::make(['title' => 'Matkul', 'data' => 'nama_mata_kuliah']),
                Column::make(['title' => 'Dosen', 'data' => 'nama_dosen']),
                Column::make(['title' => 'Jenis', 'data' => 'jenis_modul']), // Teori/Praktikum
                Column::make(['title' => 'Jml', 'data' => 'jumlah_dibutuhkan']),
                Column::make(['title' => 'Status', 'data' => 'status']),       
                Column::make(['title' => 'Aksi', 'data' => 'action']),
            ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        // You might need a separate view or reuse the existing list with a different variable
        // Assuming you create 'admin.usulan.list_modul' or reuse 'admin.usulan.list'
        return $this->view('admin.usulan.list_modul'); 
    }

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            // Ensure UsulanModul model is imported and has getDataDetail method
            $data = DataTables::of(UsulanModul::getDataDetail($filter, get: true))->toArray();            
            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']               = ++$start;
                $dt['nama_dosen']       = $value['nama_dosen']       ?? '-';
                $dt['judul_modul']      = $value['judul_modul']      ?? '-';
                $dt['nama_mata_kuliah'] = $value['nama_mata_kuliah'] ?? '-';
                $dt['jumlah_dibutuhkan']= $value['jumlah_dibutuhkan'] ?? '0';
                
                // Logic for Jenis Modul Badge
                $isPraktikum = $value['praktikum'] == 1;
                $dt['jenis_modul'] = $isPraktikum 
                    ? '<span class="badge badge-info">Praktikum</span>' 
                    : '<span class="badge badge-secondary">Teori</span>';

                // Status Badge Logic (Assuming 'Pending', 'Approved', 'Rejected' string or 0/1/2 int)
                // Adjust this depending on whether your DB stores strings or ints for status
                $statusLabel = $value['status'];
                if($value['status'] == 'Pending') $statusLabel = '<span class="badge badge-warning">Pending</span>';
                elseif($value['status'] == 'Approved') $statusLabel = '<span class="badge badge-success">Disetujui</span>';
                elseif($value['status'] == 'Rejected') $statusLabel = '<span class="badge badge-danger">Ditolak</span>';
                
                $dt['status'] = $statusLabel;

                $id = $value['reqmodul_id'];
                
                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];
                
                // Action Buttons
                if ($value['status'] == 'Pending'){
                    $dataAction['btn'] = [
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]], // distinct attr
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]],   // distinct attr
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],  // distinct attr
                    ];
                } else {
                    $dataAction['btn'] = [
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                }
                
                // Add File Download Button if file exists
                if (!empty($value['file'])) {
                    // Assuming you have a route to view/download file
                    // $dataAction['btn'][] = ['action' => 'custom', 'icon' => 'fa fa-download', 'url' => asset('storage/' . $value['file']), 'title' => 'Unduh File'];
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
            $currData = UsulanModul::findOrFail(decid($req->input('reqmodul_id')));

            $userData = $currData->toArray();
            // Optional: Add file URL to response if needed for modal
            if($currData->file) {
                $userData['file_url'] = asset('storage/' . $currData->file);
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
        
        $usulan = UsulanModul::findOrFail($req->input('reqmodul_id'));
        $usulan->status = 'Approved'; 
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

        $usulan = UsulanModul::findOrFail($req->input('reqmodul_id'));
        $usulan->status = 'Rejected'; 
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
            $currData = UsulanModul::where('reqmodul_id', $id)->firstOrFail();

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

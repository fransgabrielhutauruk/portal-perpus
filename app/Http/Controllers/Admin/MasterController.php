<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dimension\DmPegawai;
use App\Models\Dimension\Prodi;
use App\Services\Admin\PegawaiSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class MasterController extends Controller
{
    protected $sosial_medias = [];
    function __construct()
    {
        $this->activeRoot   = 'master';
        $this->breadCrump[] = ['title' => 'Master', 'link' => url('')];
    }

    function index() {}

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'pegawai') {
            $this->title        = 'Kelola Data Pegawai';
            $this->activeMenu   = 'pegawai';
            $this->breadCrump[] = ['title' => 'Pegawai', 'link' => url()->current()];

            $prodiList = Prodi::query()
                ->select('nama_prodi')
                ->whereNull('deleted_at')
                ->orderBy('nama_prodi', 'asc')
                ->get();

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/pegawai-list')->columns([
                Column::make(['title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'NIP', 'data' => 'nip']),
                Column::make(['title' => 'Nama Pegawai', 'data' => 'nama']),
                Column::make(['title' => 'Inisial', 'data' => 'inisial']),
                Column::make(['title' => 'Email', 'data' => 'email']),
                Column::make(['title' => 'Homebase', 'data' => 'homebase']),
                Column::make(['title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap text-center']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
                'prodiList' => $prodiList,
            ]);

            return $this->view('admin.master.pegawai');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'sync-pegawai') {
            try {
                $syncService = new PegawaiSyncService();
                $result = $syncService->syncPegawai();

                if ($result['success']) {
                    return response()->json([
                        'status'  => true,
                        'message' => $result['message'],
                        'data'    => [
                            'synced' => $result['synced'],
                            'failed' => $result['failed'],
                            'total'  => $result['total'] ?? 0,
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => $result['message']
                    ], 500);
                }
            } catch (\Exception $e) {
                Log::error('Pegawai sync error in controller', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'status'  => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        } elseif ($param1 == 'pegawai') {
            validate_and_response([
                'nama' => ['Nama Pegawai', 'required|max:255'],
                'inisial' => ['Inisial', 'required|max:3'],
                'nip' => ['NIP', 'required|max:50|unique:dm_pegawai,nip,NULL,pegawai_id,deleted_at,NULL'],
                'email' => ['Email', 'required|email|max:255|unique:dm_pegawai,email,NULL,pegawai_id,deleted_at,NULL'],
                'homebase' => ['Homebase', 'nullable|max:255'],
            ]);

            $data = [
                'nama' => clean_post('nama'),
                'inisial' => clean_post('inisial'),
                'nip' => clean_post('nip'),
                'email' => clean_post('email'),
                'homebase' => clean_post('homebase') ? clean_post('homebase') : null,
            ];

            DB::beginTransaction();
            try {
                $inserted = DmPegawai::create($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Pegawai berhasil ditambahkan.',
                    'data' => ['id' => encid($inserted->pegawai_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Tambah data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'pegawai') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
                'nama' => ['Nama Pegawai', 'required|max:255'],
                'inisial' => ['Inisial', 'required|max:3'],
                'nip' => ['NIP', 'required|max:50'],
                'email' => ['Email', 'required|email|max:255'],
                'homebase' => ['Homebase', 'nullable|max:255'],
            ]);

            $id = decid($req->input('id'));
            $currData = DmPegawai::findOrFail($id);

            validate_and_response([
                'nip' => ['NIP', 'unique:dm_pegawai,nip,' . $id . ',pegawai_id,deleted_at,NULL'],
                'email' => ['Email', 'email|unique:dm_pegawai,email,' . $id . ',pegawai_id,deleted_at,NULL'],
            ]);

            $data = [
                'nama' => clean_post('nama'),
                'inisial' => clean_post('inisial'),
                'nip' => clean_post('nip'),
                'email' => clean_post('email'),
                'homebase' => clean_post('homebase') ? clean_post('homebase') : null,
            ];

            DB::beginTransaction();
            try {
                $currData->update($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Pegawai berhasil diperbarui.',
                    'data' => ['id' => encid($id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Update data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'pegawai') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = DmPegawai::findOrFail(decid($req->input('id')));

            DB::beginTransaction();
            try {
                $currData->delete();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Pegawai berhasil dihapus.'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Hapus data gagal: ' . $th->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function data(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'pegawai-list') {
            $filter = [];

            $data = DataTables::of(DmPegawai::getDataDetail($filter, get: true))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']       = ++$start;
                $dt['nip']      = $value['nip'];
                $dt['nama']     = $value['nama'];
                $dt['inisial']  = $value['inisial'] ?? '-';
                $dt['homebase'] = $value['homebase'] ?? '-';
                $dt['email']    = $value['email'];

                $id = encid($value['pegawai_id']);

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
        } elseif ($param1 == 'detail' && $param2 == 'pegawai') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $id = $req->input('id');
            $currData = DmPegawai::findOrFail(decid($id))->makeHidden(DmPegawai::$exceptEdit);
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
}

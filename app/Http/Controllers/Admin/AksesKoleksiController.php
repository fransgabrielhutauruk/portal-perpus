<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AksesKoleksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class AksesKoleksiController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Akses dan Koleksi';
        $this->activeRoot = 'konten';
        $this->activeMenu = 'akses-koleksi';
        $this->breadCrump[] = ['title' => 'Akses dan Koleksi', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.akses-koleksi.data') . '/list')->columns([
            Column::make(['width' => '8%', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '10%', 'title' => 'Nama', 'data' => 'nama_akses_koleksi']),
            Column::make(['width' => '45%', 'title' => 'Deskripsi', 'data' => 'deskripsi']),
            Column::make(['width' => '20%', 'title' => 'URL', 'data' => 'url', 'orderable' => false, 'searchable' => false]),
            Column::make(['width' => '5%', 'title' => 'Urutan', 'data' => 'urutan', 'className' => 'text-center']),
            Column::make(['width' => '12%', 'title' => 'Status', 'data' => 'status', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
            ]);

        return $this->view('admin.akses_koleksi.list');
    }

    public function store(Request $req): JsonResponse
    {
        validate_and_response([
            'nama_akses_koleksi' => ['Nama Akses/Koleksi', 'required|max:255'],
            'deskripsi' => ['Deskripsi', 'nullable'],
            'url' => ['URL', 'required|url|max:255'],
            'urutan' => ['Urutan', 'required|integer|min:0'],
            'is_active' => ['Status', 'nullable|in:0,1'],
        ]);

        $data = [
            'nama_akses_koleksi' => clean_post('nama_akses_koleksi'),
            'deskripsi' => clean_post('deskripsi'),
            'url' => clean_post('url'),
            'urutan' => (int) $req->input('urutan', 0),
            'is_active' => (int) $req->input('is_active', 0),
        ];

        DB::beginTransaction();
        try {
            $inserted = AksesKoleksi::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Akses dan koleksi berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->akses_koleksi_id)]
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
            'nama_akses_koleksi' => ['Nama Akses/Koleksi', 'required|max:255'],
            'deskripsi' => ['Deskripsi', 'nullable'],
            'url' => ['URL', 'required|url|max:255'],
            'urutan' => ['Urutan', 'required|integer|min:0'],
            'is_active' => ['Status', 'nullable|in:0,1'],
        ]);

        $id = decid($req->input('id'));
        $currData = AksesKoleksi::findOrFail($id);

        $data = [
            'nama_akses_koleksi' => clean_post('nama_akses_koleksi'),
            'deskripsi' => clean_post('deskripsi'),
            'url' => clean_post('url'),
            'urutan' => (int) $req->input('urutan', 0),
            'is_active' => (int) $req->input('is_active', 0),
        ];

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Akses dan koleksi berhasil diperbarui.',
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
            'id' => ['Parameter data', 'required']
        ]);

        $currData = AksesKoleksi::findOrFail(decid($req->input('id')));

        DB::beginTransaction();
        try {
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Akses dan koleksi berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Hapus data gagal: ' . $th->getMessage());
        }
    }

    public function data(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(AksesKoleksi::getDataDetail($filter))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['nama_akses_koleksi'] = $value['nama_akses_koleksi'] ?? '-';
                $dt['deskripsi'] = $value['deskripsi'] ? Str::limit($value['deskripsi'], 120) : '-';
                $dt['url'] = $value['url']
                    ? '<a href="' . $value['url'] . '" target="_blank" class="text-primary">' . $value['url'] . '</a>'
                    : '-';
                $dt['urutan'] = $value['urutan'] ?? 0;

                if (!empty($value['is_active'])) {
                    $dt['status'] = '<span class="badge badge-light-success">Aktif</span>';
                } else {
                    $dt['status'] = '<span class="badge badge-light-secondary">Nonaktif</span>';
                }

                $id = encid($value['akses_koleksi_id']);

                $dataAction = [
                    'id' => $id,
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

            $currData = AksesKoleksi::findOrFail(decid($req->input('id')));

            $payload = $currData->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();
            $payload['id'] = $req->input('id');

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $payload
            ]);
        }

        abort(404, 'Halaman tidak ditemukan');
    }
}

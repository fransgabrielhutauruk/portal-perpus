<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Html\Column;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Berita';
        $this->activeMenu = 'berita';
        $this->breadCrump[] = ['title' => 'Berita', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.berita.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '15%', 'title' => 'Tanggal', 'data' => 'tanggal_berita', 'className' => 'text-center']),
            Column::make(['width' => '15%', 'title' => 'Status', 'data' => 'status_berita', 'className' => 'text-center', 'render' => 'renderStatus(full.status_berita)']),
            Column::make([
                'width' => '',
                'title' => 'Judul',
                'data' => 'judul_berita',
                'orderable' => true,
                'render' => '`
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-start flex-column">
                        <img src="${full.filename_berita}" class="w-100px rounded me-6"/>
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                        <div>${full.judul_berita}</div>
                        <small class="d-flex align-items-center flex-row fst-italic fs-9 text-muted">
                            <span><i class="bi bi bi-link-45deg me-2"></i><a href="${full.url_berita}" target="_blank" class="fs-7">${full.slug_berita}</a></span>
                        </small>
                    </div>
                </div>
            `'
            ]),
            Column::make(['width' => '', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.berita.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title = 'Form Berita';
            $this->activeMenu = 'berita';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            // Check if we have an ID (editing) or creating new
            if ($param2) {
                $filter['berita_id'] = decid($param2);
                $get_berita = Berita::getDataDetail($filter, false)->first();

                if (!$get_berita) {
                    abort(404, 'Berita tidak ditemukan');
                }

                $dataBerita = [
                    'id' => encid($get_berita->berita_id),
                    'judul_berita' => $get_berita->judul_berita,
                    'isi_berita' => $get_berita->isi_berita,
                    'tanggal_berita' => $get_berita->tanggal_berita ? date('Y-m-d\TH:i', strtotime($get_berita->tanggal_berita)) : null,
                    'user_id_author' => $get_berita->user_id_author,
                    'status_berita' => $get_berita->status_berita,
                    'meta_desc_berita' => $get_berita->meta_desc_berita,
                    'meta_keyword_berita' => $get_berita->meta_keyword_berita,
                    'slug_berita' => $get_berita->slug_berita,
                    'media_cover' => $get_berita->filename_berita ? asset('uploads/berita/' . $get_berita->filename_berita) : null,
                ];
            } else {
                // Creating new berita - set default values
                $dataBerita = [
                    'id' => '',
                    'judul_berita' => '',
                    'isi_berita' => '',
                    'tanggal_berita' => date('Y-m-d\TH:i'),
                    'user_id_author' => Auth::id(),
                    'status_berita' => 'draft',
                    'meta_desc_berita' => '',
                    'meta_keyword_berita' => '',
                    'slug_berita' => '',
                    'media_cover' => null,
                ];
            }

            $this->dataView([
                'dataBerita' => $dataBerita
            ]);

            return $this->view('admin.berita.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req): JsonResponse
    {
        validate_and_response([
            'judul_berita' => ['Judul Berita', 'required|max:255'],
            'status_berita' => ['Status Berita', 'required|in:draft,published,archived'],
        ]);

        $slug_berita = createSlug(clean_post('judul_berita'));

        $data = [
            'judul_berita' => clean_post('judul_berita'),
            'isi_berita' => 'Isi berita akan diisi nanti...',
            'tanggal_berita' => clean_post('tanggal_berita') ? date('Y-m-d H:i:s', strtotime(clean_post('tanggal_berita'))) : date('Y-m-d H:i:s'),
            'user_id_author' => Auth::id(),
            'status_berita' => clean_post('status_berita'),
            'slug_berita' => $slug_berita,
            'created_by' => Auth::id(),
        ];

        DB::beginTransaction();
        try {
            $inserted = Berita::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berita berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->berita_id)]
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
            'judul_berita' => ['Judul Berita', 'required|max:255'],
            'isi_berita' => ['Isi Berita', 'required'],
            'tanggal_berita' => ['Tanggal Berita', 'required|date'],
            'status_berita' => ['Status Berita', 'required|in:draft,published,archived'],
            'meta_desc_berita' => ['Meta Description', 'nullable|max:160'],
            'meta_keyword_berita' => ['Meta Keywords', 'nullable|max:255'],
        ]);

        $id = decid($req->input('id'));
        $currData = Berita::findOrFail($id);

        $slug_berita = createSlug(clean_post('judul_berita'));

        $data = [
            'judul_berita' => clean_post('judul_berita'),
            'isi_berita' => $req->isi_berita,
            'tanggal_berita' => clean_post('tanggal_berita') ? date('Y-m-d H:i:s', strtotime(clean_post('tanggal_berita'))) : null,
            'status_berita' => clean_post('status_berita'),
            'meta_desc_berita' => clean_post('meta_desc_berita'),
            'meta_keyword_berita' => clean_post('meta_keyword_berita'),
            'slug_berita' => $slug_berita,
            'updated_by' => Auth::id(),
        ];

        if ($req->hasFile('upload_file')) {
            $do_upload = uploadMedia('upload_file', 'berita');
            if (!$do_upload['status'])
                abort(500, 'Update data gagal, ' . $do_upload['message']);

            $data['filename_berita'] = $do_upload['data']['filename'];
        }

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berita berhasil diperbarui.',
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

        $currData = Berita::findOrFail(decid($req->input('id')));

        DB::beginTransaction();
        try {
            $currData->update(['deleted_by' => Auth::id()]);
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berita berhasil dihapus.'
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
            $data = DataTables::of(Berita::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['judul_berita'] = $value['judul_berita'] ?? '-';
                $dt['tanggal_berita'] = $value['tanggal_berita'] ? tanggal($value['tanggal_berita']) : '-';
                $dt['status_berita'] = $value['status_berita'] ?? '-';
                $dt['author_name'] = $value['author_name'] ?? '-';
                $dt['filename_berita'] = publicMedia($value['filename_berita'], 'berita');
                $dt['slug_berita'] = $value['slug_berita'] ?? '#';
                $dt['url_berita'] = $value['slug_berita'] ? url('berita/' . $value['slug_berita']) : '#';



                $id = encid($value['berita_id']);

                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.berita.show', ['param1' => 'form', 'param2' => $id])],
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

            $currData = Berita::findOrFail(decid($req->input('id')))->makeHidden(Berita::$exceptEdit);
            $currData->id = $req->input('id');
            $currData->tanggal_berita = $currData->tanggal_berita ? date('Y-m-d\TH:i', strtotime($currData->tanggal_berita)) : null;

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

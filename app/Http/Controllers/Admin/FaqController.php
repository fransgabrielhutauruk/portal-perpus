<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class FaqController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola FAQ';
        $this->activeMenu = 'faq';
        $this->breadCrump[] = ['title' => 'FAQ', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.faq.data') . '/list')->columns([
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '35%', 'title' => 'Pertanyaan', 'data' => 'pertanyaan']),
            Column::make(['width' => '', 'title' => 'Jawaban', 'data' => 'jawaban']),
            Column::make(['width' => '15%', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.faq.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title = 'Form FAQ';
            $this->activeMenu = 'faq';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            if ($param2) {
                $filter['faq_id'] = decid($param2);
                $get_faq = Faq::getDataDetail($filter, false)->first();

                if (!$get_faq) {
                    abort(404, 'FAQ tidak ditemukan');
                }

                $dataFaq = [
                    'id' => encid($get_faq->faq_id),
                    'pertanyaan' => $get_faq->pertanyaan,
                    'jawaban' => $get_faq->jawaban,
                ];
            } else {
                $dataFaq = [
                    'id' => '',
                    'pertanyaan' => '',
                    'jawaban' => '',
                ];
            }

            $this->dataView([
                'dataFaq' => $dataFaq
            ]);

            return $this->view('admin.faq.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function store(Request $req): JsonResponse
    {
        validate_and_response([
            'pertanyaan' => ['Pertanyaan', 'required'],
            'jawaban' => ['Jawaban', 'required'],
        ]);

        $data = [
            'pertanyaan' => clean_post('pertanyaan'),
            'jawaban' => clean_post('jawaban'),
            'created_by' => Auth::id(),
        ];

        DB::beginTransaction();
        try {
            $inserted = Faq::create($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'FAQ berhasil ditambahkan.',
                'data' => ['id' => encid($inserted->faq_id)]
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
            'pertanyaan' => ['Pertanyaan', 'required'],
            'jawaban' => ['Jawaban', 'required'],
        ]);

        $id = decid($req->input('id'));
        $currData = Faq::findOrFail($id);

        $data = [
            'pertanyaan' => clean_post('pertanyaan'),
            'jawaban' => clean_post('jawaban'),
            'updated_by' => Auth::id(),
        ];

        DB::beginTransaction();
        try {
            $currData->update($data);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'FAQ berhasil diperbarui.',
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

        $id = decid($req->input('id'));
        $currData = Faq::findOrFail($id);

        DB::beginTransaction();
        try {
            $currData->deleted_by = Auth::id();
            $currData->save();
            $currData->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'FAQ berhasil dihapus.'
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
            $data = DataTables::of(Faq::getDataDetail($filter))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['pertanyaan'] = Str::limit($value['pertanyaan'] ?? '-', 80);
                $dt['jawaban'] = Str::limit($value['jawaban'] ?? '-', 100);

                $id = encid($value['faq_id']);

                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.faq.show', ['param1' => 'form', 'param2' => $id])],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);
                $resp[] = $dt;
            }

            $data['data'] = $resp;

            return response()->json($data);
        }
        abort(404, 'Halaman tidak ditemukan');
    }
}

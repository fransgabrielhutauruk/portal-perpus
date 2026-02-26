<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
use App\Models\Kaperpus;
use App\Models\ReqBebasPustaka;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use PhpOffice\PhpWord\TemplateProcessor;

class ReqBebasPustakaController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Bebas Pustaka';
        $this->activeMenu = 'req-bebas-pustaka';
        $this->breadCrump[] = ['title' => 'Request Bebas Pustaka', 'link' => url()->current()];

        $roles = Role::all();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.req-bebas-pustaka.data') . '/list')->columns([
            Column::make(['title' => 'No', 'data' => 'no']),
            Column::make(['title' => 'Dikirim Pada', 'data' => 'dikirim_pada']),
            Column::make(['title' => 'Nama Mahasiswa', 'data' => 'nama_mahasiswa']),
            Column::make(['title' => 'NIM', 'data' => 'nim']),
            Column::make(['title' => 'Prodi', 'data' => 'prodi_nama']),
            Column::make(['title' => 'Bukti', 'data' => 'bukti']),
            Column::make(['title' => 'Status', 'data' => 'status']),
            Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'roles' => $roles
        ]);

        return $this->view('admin.req.bebas_pustaka');
    }

    public function show($param1 = '', $param2 = '')
    {
        // Kaperpus data list (DataTable AJAX)
        if ($param1 == 'kaperpus' && $param2 == 'list') {
            return $this->kaperpusData(request());
        }

        // Kaperpus set active
        if ($param1 == 'kaperpus' && $param2 == 'set-active') {
            return $this->kaperpusSetActive(request());
        }

        // Kaperpus page view
        if ($param1 == 'kaperpus' && $param2 == '') {
            $this->title = 'Kelola Kaperpus';
            $this->activeMenu = 'kaperpus';
            $this->breadCrump[] = ['title' => 'Request Bebas Pustaka', 'link' => route('app.req-bebas-pustaka.index')];
            $this->breadCrump[] = ['title' => 'Kelola Kaperpus', 'link' => url()->current()];

            $builder = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.req-bebas-pustaka.show', ['param1' => 'kaperpus', 'param2' => 'list']))->columns([
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'Nama Kaperpus', 'data' => 'nama_kaperpus']),
                Column::make(['title' => 'Tanda Tangan', 'data' => 'ttd_kaperpus']),
                Column::make(['title' => 'Status', 'data' => 'status']),
                Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
            ]);

            return $this->view('admin.req.kaperpus');
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    // ========================
    // KAPERPUS CRUD METHODS
    // ========================

    private function kaperpusData(Request $req): JsonResponse
    {
        $filter = [];
        $data = DataTables::of(Kaperpus::getDataDetail($filter, get: true))->toArray();
        $start = $req->input('start');
        $resp = [];
        foreach ($data['data'] as $key => $value) {
            $dt = [];

            $dt['no'] = ++$start;
            $dt['nama_kaperpus'] = $value['nama_kaperpus'] ?? '-';
            $dt['ttd_kaperpus'] = '<img src="' . publicMedia($value['ttd_kaperpus'], 'ttd_kaperpus') . '" class="w-150px">';

            $dt['status'] = $value['is_active']
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Tidak Aktif</span>';

            $id = encid($value['kaperpus_id']);

            $btns = [
                ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
                ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
            ];

            if (!$value['is_active']) {
                array_unshift($btns, ['action' => 'to_active', 'attr' => ['jf-set-active' => $value['kaperpus_id']], 'label' => 'Set Aktif', 'class' => 'btn-sm btn-success']);
            }

            $dataAction = [
                'id'  => $id,
                'btn' => $btns,
            ];

            $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);
            $resp[] = $dt;
        }

        $data['data'] = $resp;

        return response()->json($data);
    }

    private function kaperpusSetActive(Request $req): JsonResponse
    {
        validate_and_response([
            'kaperpus_id' => ['ID Kaperpus', 'required'],
        ]);

        DB::beginTransaction();
        try {
            // Nonaktifkan semua kaperpus
            Kaperpus::where('is_active', true)->update(['is_active' => false]);

            // Aktifkan yang dipilih
            $kaperpus = Kaperpus::findOrFail($req->input('kaperpus_id'));
            $kaperpus->is_active = true;
            $kaperpus->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Kaperpus berhasil diaktifkan.'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            abort(404, 'Gagal mengaktifkan kaperpus, ' . $th->getMessage());
        }
    }

    public function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'kaperpus') {
            validate_and_response([
                'nama_kaperpus' => ['Nama Kaperpus', 'required'],
            ]);

            $data['nama_kaperpus'] = clean_post('nama_kaperpus');
            $data['is_active'] = false;

            // if ($req->hasFile('ttd_kaperpus')) {
            //     $data['ttd_kaperpus'] = $req->file('ttd_kaperpus')->store('uploads/kaperpus', 'public');
            // }

            if ($req->hasFile('ttd_kaperpus')) {
                $do_upload = uploadMedia('ttd_kaperpus', 'ttd_kaperpus');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);
                $data['ttd_kaperpus'] = $do_upload['data']['filename'];
            }

            DB::beginTransaction();
            try {
                $inserted = Kaperpus::create($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data kaperpus berhasil ditambah.',
                    'data' => ['kaperpus_id' => encid($inserted->kaperpus_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Tambah data gagal, ' . $th->getMessage());
            }
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    public function update(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'kaperpus') {
            validate_and_response([
                'nama_kaperpus' => ['Nama Kaperpus', 'required'],
            ]);

            $id = $req->input('kaperpus_id');
            $currData = Kaperpus::findOrFail($id);

            $data['nama_kaperpus'] = $req->input('nama_kaperpus');

            if ($req->hasFile('ttd_kaperpus')) {
                $do_upload = uploadMedia('ttd_kaperpus', 'ttd_kaperpus');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);
                $data['ttd_kaperpus'] = $do_upload['data']['filename'];
            }

            DB::beginTransaction();
            try {
                $currData->update($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Update data berhasil.',
                    'data' => ['kaperpus_id' => $id]
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    public function data(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $data = DataTables::of(ReqBebasPustaka::getDataDetail($filter, get: true))->toArray();
            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['dikirim_pada'] = $value['created_at'] ? date('d-m-Y H:i', strtotime($value['created_at'])) : '-';
                $dt['nama_mahasiswa'] = $value['nama_mahasiswa'] ?? '-';
                $dt['nim'] = $value['nim'] ?? '-';
                $dt['prodi_nama'] = $value['nama_prodi'] ?? '-';
                $dt['bukti'] = '<a href="' . ($value['link_kp_repository'] ?? '#') . '" target="_blank">Link Repository KP</a><br>' .
                    '<a href="' . ($value['link_pa_repository'] ?? '#') . '" target="_blank">Link Repository PA</a>';
                $dt['status'] = ReqBebasPustaka::getStatusBadge($value['status_req'] ?? null);

                $bebasPustaka = ReqBebasPustaka::find($value['reqbebaspustaka_id']);
                $id = $value['reqbebaspustaka_id'];

                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];

                if ($bebasPustaka && $bebasPustaka->status_req == StatusRequest::MENUNGGU->value) {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]],
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                } elseif ($bebasPustaka && $bebasPustaka->status_req == StatusRequest::DISETUJUI->value) {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'download', 'attr' => ['jf-download' => $id], 'label' => 'Download DOCX'],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                } else {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                }

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);
                $resp[] = $dt;
            }

            $data['data'] = $resp;

            return response()->json($data);
        } else if ($param1 == 'detail' && $param2 == 'kaperpus') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $currData = Kaperpus::findOrFail(decid($req->input('id')));

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $currData->toArray()
            ]);
        } else if ($param1 == 'detail') {
            validate_and_response([
                'reqbebaspustaka_id' => ['Parameter data', 'required'],
            ]);
            $currData = ReqBebasPustaka::with('prodi')->findOrFail($req->input('reqbebaspustaka_id'));

            $userData = $currData->toArray();
            $userData['prodi_nama'] = $currData->prodi->nama_prodi ?? '-';

            if ($currData->file_hasil_bebas_pustaka) {
                $userData['file_url'] = asset($currData->file_hasil_bebas_pustaka);
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
            'reqbebaspustaka_id' => ['ID Request Bebas Pustaka', 'required'],
        ]);

        $bebasPustaka = ReqBebasPustaka::with('periode', 'prodi')->findOrFail($req->input('reqbebaspustaka_id'));

        //    1. LOAD TEMPLATE DOCX
        $templatePath = storage_path('app/private/template_bebas_pustaka.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        //    2. REPLACE PLACEHOLDER
        $templateProcessor->setValue('nama', $bebasPustaka->nama_mahasiswa);
        $templateProcessor->setValue('nim', $bebasPustaka->nim ?? '-');
        $templateProcessor->setValue('prodi', $bebasPustaka->prodi->nama_prodi ?? '-');
        $templateProcessor->setValue('tahun', $bebasPustaka->periode->tanggal_selesai ? \Carbon\Carbon::parse($bebasPustaka->periode->tanggal_selesai)->format('Y') : '-');
        $templateProcessor->setValue('tanggal', tanggal(now(), ' ', false) ?? '-');
        $kaperpus = Kaperpus::getActive();
        $templateProcessor->setValue('kaperpus', $kaperpus ? $kaperpus->nama_kaperpus : '-');

        // Set tanda tangan jika ada
        if ($kaperpus && $kaperpus->ttd_kaperpus && file_exists(storage_path('app/public/ttd_kaperpus/' . $kaperpus->ttd_kaperpus))) {
            $templateProcessor->setImageValue('ttd_kaperpus', [
                'path' => storage_path('app/public/ttd_kaperpus/' . $kaperpus->ttd_kaperpus),
                'width' => 150,
                'height' => 75,
            ]);
        }

        //    3. SAVE DOCX RESULT
        $docxName = 'bebas_pustaka_' . $bebasPustaka->nim . '_' . time() . '.docx';
        $docxPath = storage_path('app/private/' . $docxName);
        $templateProcessor->saveAs($docxPath);

        //    4. UPDATE DATABASE
        $bebasPustaka->file_hasil_bebas_pustaka = 'storage/private/' . $docxName;
        $bebasPustaka->status_req = StatusRequest::DISETUJUI->value;
        $bebasPustaka->is_syarat_terpenuhi = true;
        $bebasPustaka->save();

        return response()->json([
            'status' => true,
            'message' => 'Request bebas pustaka telah disetujui. File DOCX siap untuk diunduh.'
        ]);
    }

    public function reject(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbebaspustaka_id' => ['ID Request Bebas Pustaka', 'required'],
            'catatan_admin' => ['Alasan Penolakan', 'required'],
        ]);

        $bebasPustaka = ReqBebasPustaka::findOrFail($req->input('reqbebaspustaka_id'));
        $bebasPustaka->status_req = StatusRequest::DITOLAK->value;
        $bebasPustaka->catatan_admin = $req->input('catatan_admin') ?? '-';
        $bebasPustaka->save();

        return response()->json([
            'status' => true,
            'message' => 'Request bebas pustaka telah ditolak.'
        ]);
    }

    public function download(Request $req)
    {
        validate_and_response([
            'reqbebaspustaka_id' => ['ID Request Bebas Pustaka', 'required'],
        ]);

        $bebasPustaka = ReqBebasPustaka::findOrFail($req->input('reqbebaspustaka_id'));

        if ($bebasPustaka->status_req != StatusRequest::DISETUJUI->value) {
            abort(403, 'File hanya dapat diunduh untuk request yang sudah disetujui.');
        }

        if (!$bebasPustaka->file_hasil_bebas_pustaka) {
            abort(404, 'File tidak ditemukan. Silakan approve ulang request ini.');
        }

        $filePath = storage_path('app/private/' . basename($bebasPustaka->file_hasil_bebas_pustaka));

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage. Silakan approve ulang request ini.');
        }

        $fileName = 'Surat_Bebas_Pustaka_' . $bebasPustaka->nim . '.docx';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function reset(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbebaspustaka_id' => ['ID Request Bebas Pustaka', 'required'],
        ]);

        $bebasPustaka = ReqBebasPustaka::findOrFail($req->input('reqbebaspustaka_id'));
        $bebasPustaka->status_req = StatusRequest::MENUNGGU->value;
        $bebasPustaka->catatan_admin = null;
        $bebasPustaka->save();

        return response()->json([
            'status' => true,
            'message' => 'Status request telah direset ke menunggu.'
        ]);
    }

    public function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'kaperpus') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $id = $req->input('id');
            $currData = Kaperpus::findOrFail(decid($id));

            DB::beginTransaction();
            try {
                $currData->delete();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data kaperpus berhasil dihapus'
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404, 'Hapus data gagal, ' . $th->getMessage());
            }
        } elseif ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $id = $req->input('id');

            $currData = ReqBebasPustaka::where('reqbebaspustaka_id', $id)->firstOrFail();

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

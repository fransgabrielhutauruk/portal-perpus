<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
use App\Http\Controllers\Controller;
use App\Models\Kaperpus;
use App\Models\Periode;
use App\Models\ReqBebasPustaka;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class ReqBebasPustakaController extends Controller
{
    public function index()
    {
        $this->title = 'Kelola Request Bebas Pustaka';
        $this->activeMenu = 'req-bebas-pustaka';
        $this->breadCrump[] = ['title' => 'Request Bebas Pustaka', 'link' => url()->current()];

        $periodeReqBebasPustaka = Periode::query()
            ->select(['periode_id', 'nama_periode'])
            ->where('jenis_periode', Periode::TYPE_REQ_BEBAS_PUSTAKA)
            ->whereNull('deleted_at')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.req-bebas-pustaka.data') . '/list')->columns([
            Column::make(['title' => 'No', 'data' => 'no']),
            Column::make(['title' => 'Dikirim Pada', 'data' => 'dikirim_pada', 'orderable' => false]),
            Column::make(['title' => 'Nama Mahasiswa', 'data' => 'nama_mahasiswa']),
            Column::make(['title' => 'NIM', 'data' => 'nim']),
            Column::make(['title' => 'Prodi', 'data' => 'prodi_nama']),
            Column::make(['title' => 'Bukti', 'data' => 'bukti']),
            Column::make(['title' => 'Status', 'data' => 'status']),
            Column::make(['title' => 'Aksi', 'data' => 'action', 'class' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
            'periodeReqBebasPustaka' => $periodeReqBebasPustaka,
        ]);

        return $this->view('admin.req.bebas_pustaka');
    }

    public function show(string $param1 = '', string $param2 = '')
    {
        if ($param1 == 'kaperpus' && $param2 == 'list') {
            return $this->kaperpusData(request());
        }

        if ($param1 == 'kaperpus' && $param2 == 'set-active') {
            return $this->kaperpusSetActive(request());
        }

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


    private function kaperpusData(Request $req): JsonResponse
    {
        $filter = [];
        $data = DataTables::of(Kaperpus::getDataDetail($filter, get: true))->toArray();
        $start = (int) $req->input('start', 0);
        $resp = [];
        foreach ($data['data'] as $value) {
            $dt = [];

            $dt['no'] = ++$start;
            $dt['nama_kaperpus'] = $value['nama_kaperpus'] ?? '-';
            $dt['ttd_kaperpus'] = '<img src="' . publicMedia($value['ttd_kaperpus'], 'ttd_kaperpus') . '" style="height: 100px; object-fit:cover;">';

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
            Kaperpus::where('is_active', true)->update(['is_active' => false]);

            $kaperpus = Kaperpus::findOrFail((int) $req->input('kaperpus_id'));
            $kaperpus->is_active = true;
            $kaperpus->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Kaperpus berhasil diaktifkan.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Gagal mengaktifkan kaperpus: ' . $th->getMessage());
        }
    }

    public function store(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'kaperpus') {
            validate_and_response([
                'nama_kaperpus' => ['Nama Kaperpus', 'required'],
                'ttd_kaperpus' => ['Tanda Tangan', 'required|image|mimes:jpg,jpeg,png|max:2048'],
            ]);

            $data = [
                'nama_kaperpus' => clean_post('nama_kaperpus'),
                'is_active' => false,
            ];

            if ($req->hasFile('ttd_kaperpus')) {
                $do_upload = uploadMedia('ttd_kaperpus', 'ttd_kaperpus');
                if (!$do_upload['status']) {
                    abort(500, 'Update data gagal, ' . $do_upload['message']);
                }

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
                DB::rollBack();
                abort(500, 'Tambah data gagal: ' . $th->getMessage());
            }
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    public function update(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'kaperpus') {
            validate_and_response([
                'kaperpus_id' => ['Parameter data', 'required'],
                'nama_kaperpus' => ['Nama Kaperpus', 'required'],
            ]);

            $id = (int) $req->input('kaperpus_id');
            $currData = Kaperpus::findOrFail($id);

            $data = [
                'nama_kaperpus' => clean_post('nama_kaperpus'),
            ];

            if ($req->hasFile('ttd_kaperpus')) {
                $do_upload = uploadMedia('ttd_kaperpus', 'ttd_kaperpus');
                if (!$do_upload['status']) {
                    abort(500, 'Update data gagal, ' . $do_upload['message']);
                }

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
                DB::rollBack();
                abort(500, 'Update data gagal: ' . $th->getMessage());
            }
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    /**
     * Return req bebas pustaka and kaperpus detail payload.
     */
    public function data(Request $req, string $param1 = '', string $param2 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $filter = [];
            $filterPeriodeId = (string) $req->input('filter_periode_id', 'all');

            if ($filterPeriodeId !== 'all' && !ctype_digit($filterPeriodeId)) {
                $filterPeriodeId = 'all';
            }

            $data = DataTables::of(ReqBebasPustaka::getDataDetail($filter, get: true, periodeId: $filterPeriodeId))->toArray();
            $start = (int) $req->input('start', 0);
            $resp = [];
            foreach ($data['data'] as $value) {
                $dt = [];
                $id = (int) ($value['reqbebaspustaka_id'] ?? 0);
                $statusReq = (int) ($value['status_req'] ?? StatusRequest::MENUNGGU->value);

                $dt['no'] = ++$start;
                $dt['dikirim_pada'] = $value['created_at'] ? date('d-m-Y H:i', strtotime($value['created_at'])) : '-';
                $dt['nama_mahasiswa'] = $value['nama_mahasiswa'] ?? '-';
                $dt['nim'] = $value['nim'] ?? '-';
                $dt['prodi_nama'] = $value['nama_prodi'] ?? '-';
                $dt['bukti'] = '<a href="' . ($value['link_kp_repository'] ?? '#') . '" target="_blank">Link Repository KP</a><br>' .
                    '<a href="' . ($value['link_pa_repository'] ?? '#') . '" target="_blank">Link Repository PA</a>';
                $dt['status'] = ReqBebasPustaka::getStatusBadge($statusReq);

                $dataAction = [
                    'id'  => encid($id),
                    'btn' => [],
                ];

                if ($statusReq === StatusRequest::MENUNGGU->value) {
                    $dataAction['btn'] = [
                        ['action' => 'detail', 'attr' => ['jf-detail' => $id]],
                        ['action' => 'approve', 'attr' => ['jf-approve' => $id]],
                        ['action' => 'reject', 'attr' => ['jf-reject' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ];
                } elseif ($statusReq === StatusRequest::DISETUJUI->value) {
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
        }

        if ($param1 == 'detail' && $param2 == 'kaperpus') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Kaperpus::findOrFail(decid($req->input('id')));

            return response()->json([
                'status' => true,
                'message' => 'Data loaded',
                'data' => $currData->toArray()
            ]);
        }

        if ($param1 == 'detail') {
            validate_and_response([
                'reqbebaspustaka_id' => ['Parameter data', 'required'],
            ]);

            $currData = ReqBebasPustaka::with('prodi')->findOrFail((int) $req->input('reqbebaspustaka_id'));

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
        }

        abort(404, 'Halaman tidak ditemukan');
    }

    public function approve(Request $req): JsonResponse
    {
        validate_and_response([
            'reqbebaspustaka_id' => ['ID Request Bebas Pustaka', 'required'],
        ]);

        $bebasPustaka = ReqBebasPustaka::with('periode', 'prodi')->findOrFail((int) $req->input('reqbebaspustaka_id'));

        $templatePath = storage_path('app/private/template_bebas_pustaka.docx');
        if (!file_exists($templatePath)) {
            abort(500, 'Template surat bebas pustaka tidak ditemukan.');
        }

        $privateDir = storage_path('app/private');
        if (!is_dir($privateDir) && !mkdir($privateDir, 0755, true) && !is_dir($privateDir)) {
            abort(500, 'Gagal menyiapkan folder penyimpanan dokumen.');
        }

        DB::beginTransaction();
        try {
        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $bebasPustaka->nama_mahasiswa);
        $templateProcessor->setValue('nim', $bebasPustaka->nim ?? '-');
        $templateProcessor->setValue('prodi', $bebasPustaka->prodi->nama_prodi ?? '-');
        $templateProcessor->setValue('tahun', $bebasPustaka->periode->tanggal_selesai ? Carbon::parse($bebasPustaka->periode->tanggal_selesai)->format('Y') : '-');
        $templateProcessor->setValue('tanggal', tanggal(now(), ' ', false) ?? '-');
        $kaperpus = Kaperpus::getActive();
        $templateProcessor->setValue('kaperpus', $kaperpus ? $kaperpus->nama_kaperpus : '-');

        if ($kaperpus && $kaperpus->ttd_kaperpus && file_exists(storage_path('app/public/ttd_kaperpus/' . $kaperpus->ttd_kaperpus))) {
            $templateProcessor->setImageValue('ttd_kaperpus', [
                'path' => storage_path('app/public/ttd_kaperpus/' . $kaperpus->ttd_kaperpus),
                'width' => 150,
                'height' => 75,
            ]);
        }

        $docxName = 'bebas_pustaka_' . $bebasPustaka->nim . '_' . time() . '.docx';
        $docxPath = storage_path('app/private/' . $docxName);
        $templateProcessor->saveAs($docxPath);

        $bebasPustaka->file_hasil_bebas_pustaka = 'storage/private/' . $docxName;
        $bebasPustaka->status_req = StatusRequest::DISETUJUI->value;
        $bebasPustaka->is_syarat_terpenuhi = true;
        $bebasPustaka->save();

        DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses persetujuan gagal: ' . $th->getMessage());
        }

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

        DB::beginTransaction();
        try {
            $bebasPustaka = ReqBebasPustaka::findOrFail((int) $req->input('reqbebaspustaka_id'));
            $bebasPustaka->status_req = StatusRequest::DITOLAK->value;
            $bebasPustaka->catatan_admin = clean_post('catatan_admin');
            $bebasPustaka->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses penolakan gagal: ' . $th->getMessage());
        }

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

        $bebasPustaka = ReqBebasPustaka::findOrFail((int) $req->input('reqbebaspustaka_id'));

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

        DB::beginTransaction();
        try {
            $bebasPustaka = ReqBebasPustaka::findOrFail((int) $req->input('reqbebaspustaka_id'));
            $bebasPustaka->status_req = StatusRequest::MENUNGGU->value;
            $bebasPustaka->catatan_admin = null;
            $bebasPustaka->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, 'Proses reset status gagal: ' . $th->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Status request telah direset ke menunggu.'
        ]);
    }

    /**
     * Delete kaperpus or req bebas pustaka data.
     */
    public function destroy(Request $req, string $param1 = ''): JsonResponse
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
                DB::rollBack();
                abort(500, 'Hapus data gagal: ' . $th->getMessage());
            }
        }

        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);
            $id = (int) $req->input('id');

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
                DB::rollBack();
                abort(500, 'Hapus data gagal: ' . $th->getMessage());
            }
        }

        abort(404, 'Halaman tidak ditemukan');
    }
}

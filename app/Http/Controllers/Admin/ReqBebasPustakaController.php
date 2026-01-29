<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusRequest;
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

    public function data(Request $req, $param1 = ''): JsonResponse
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
        } else if ($param1 = 'detail') {
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
        $templateProcessor->setValue('tanggal', tanggal($bebasPustaka->created_at, ' ', false) ?? '-');
        $templateProcessor->setValue('kaperpus', "Nina Fadilah Najwa, S.Kom, M.Kom." ?? '-');

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
        if ($param1 == '') {
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\View\View;

class ActivityLogController extends Controller
{

    public function index()
    {
        $this->title = 'Activity Log';
        $this->activeMenu = 'activity-log';
        $this->breadCrump[] = ['title' => 'Activity Log', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)
            ->ajax(route('app.activity-log.data') . '/list')
            ->columns([
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['width' => '15%', 'title' => 'Waktu', 'data' => 'created_at', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '12%', 'title' => 'User', 'data' => 'causer_name']),
                Column::make(['width' => '', 'title' => 'Aktivitas', 'data' => 'description']),
                Column::make(['title' => 'Subject', 'data' => 'subject_type', 'className' => 'text-center']),
                Column::make(['width' => '15%', 'title' => 'Detail', 'data' => 'detail', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            ]);

        // Get distinct users for filter dropdown
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        $this->dataView([
            'dataTable' => $dataTable,
            'users' => $users,
        ]);

        return $this->view('admin.activity-log.list');
    }

    public function data(Request $req, string $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $tableName = config('activitylog.table_name', 'sys_activity_log');

            $query = DB::table($tableName . ' as a')
                ->selectRaw('a.id, a.log_name, a.description, a.subject_type, a.subject_id, a.event, a.causer_type, a.causer_id, a.properties, a.created_at, u.name as causer_name')
                ->leftJoin('users as u', 'a.causer_id', '=', 'u.id');

            if ($req->filled('filter_user')) {
                $query->where('a.causer_id', $req->input('filter_user'));
            }

            if ($req->filled('filter_event')) {
                $filterEvent = $req->input('filter_event');
                $descKeywordMap = [
                    'login' => 'login',
                    'logout' => 'logout',
                    'created' => 'menambah',
                    'updated' => 'mengubah',
                    'deleted' => 'menghapus',
                ];

                if (isset($descKeywordMap[$filterEvent])) {
                    // Prefer exact event column when available, fallback for legacy rows.
                    $query->where(function ($q) use ($filterEvent, $descKeywordMap) {
                        $q->where('a.event', $filterEvent)
                            ->orWhere('a.description', 'like', '%' . $descKeywordMap[$filterEvent] . '%');
                    });
                }
            }

            $dateFrom = $req->input('filter_date_from');
            $dateTo = $req->input('filter_date_to');
            if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
                [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
            }

            if ($dateFrom) {
                $query->whereDate('a.created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('a.created_at', '<=', $dateTo);
            }

            $data = DataTables::of($query->latest('a.created_at')->get())
                ->toArray();
            $start = $req->input('start');
            $resp = [];

            foreach ($data['data'] as $value) {
                $dt = [];
                $dt['no'] = ++$start;
                $dt['created_at'] = $value['created_at'] ? date('d M Y H:i:s', strtotime($value['created_at'])) : '-';
                $dt['causer_name'] = $value['causer_name'] ?? '<span class="text-muted fst-italic">System</span>';
                $dt['description'] = $value['description'] ?? '-';

                $subjectType = $value['subject_type'] ?? null;
                if ($subjectType) {
                    $shortName = class_basename($subjectType);
                    $dt['subject_type'] = '<span class="badge badge-light-info">' . $shortName . '</span>';
                } else {
                    $dt['subject_type'] = '-';
                }

                $rawProps = $value['properties'] ?? null;
                $properties = is_string($rawProps)
                    ? (json_decode($rawProps, true) ?: [])
                    : (is_array($rawProps) ? $rawProps : []);

                $dt['detail'] = '<button type="button" class="btn btn-sm btn-light-primary btn-detail" data-id="' . $value['id'] . '"><i class="bi bi-eye"></i> Detail</button>';

                $resp[] = $dt;
            }

            $data['data'] = $resp;
            return response()->json($data);
        }

        if ($param1 == 'detail') {
            validate_and_response(['id' => ['Parameter data', 'required']]);

            $tableName = config('activitylog.table_name', 'sys_activity_log');
            $activity = DB::table($tableName . ' as a')
                ->selectRaw('a.*, u.name as causer_name, u.email as causer_email')
                ->leftJoin('users as u', 'a.causer_id', '=', 'u.id')
                ->where('a.id', (int) $req->input('id'))
                ->first();

            if (!$activity) {
                abort(404, 'Data tidak ditemukan');
            }

            $properties = $activity->properties ? json_decode($activity->properties, true) : [];

            $result = [
                'id' => $activity->id,
                'log_name' => $activity->log_name,
                'description' => $activity->description,
                'subject_type' => $activity->subject_type ? class_basename($activity->subject_type) : null,
                'subject_id' => $activity->subject_id,
                'causer_name' => $activity->causer_name ?? 'System',
                'user_email' => $activity->causer_email ?? null,
                'created_at' => date('d M Y H:i:s', strtotime($activity->created_at)),
                'properties' => $properties,
                'old' => $properties['old'] ?? null,
                'attributes' => $properties['attributes'] ?? null,
                'ip' => $properties['ip'] ?? null,
                'user_agent' => $properties['user_agent'] ?? null,
                'provider' => $properties['provider'] ?? null,
            ];

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $result]);
        }

        abort(404, 'Halaman tidak ditemukan');
    }
}

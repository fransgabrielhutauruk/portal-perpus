<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->activeRoot = '';
        $this->breadCrump[] = ['title' => 'Admin', 'link' => url('app/dashboard')];
    }

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
                Column::make(['width' => '15%', 'title' => 'Waktu', 'data' => 'created_at', 'className' => 'text-center']),
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

    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $tableName = config('activitylog.table_name', 'sys_activity_log');

            $query = DB::table($tableName . ' as a')
                ->selectRaw('a.id, a.log_name, a.description, a.subject_type, a.subject_id, a.causer_type, a.causer_id, a.properties, a.created_at, u.name as causer_name')
                ->leftJoin('users as u', 'a.causer_id', '=', 'u.id');

            // Apply filters
            if ($req->filled('filter_user')) {
                $query->where('a.causer_id', $req->input('filter_user'));
            }

            if ($req->filled('filter_event')) {
                $filterEvent = $req->input('filter_event');
                if ($filterEvent == 'login') {
                    $query->where('a.description', 'like', '%login%');
                } elseif ($filterEvent == 'logout') {
                    $query->where('a.description', 'like', '%logout%');
                } elseif ($filterEvent == 'created') {
                    $query->where('a.description', 'like', '%menambahkan%');
                } elseif ($filterEvent == 'updated') {
                    $query->where('a.description', 'like', '%merubah%');
                } elseif ($filterEvent == 'deleted') {
                    $query->where('a.description', 'like', '%menghapus%');
                }
            }

            if ($req->filled('filter_date_from')) {
                $query->whereDate('a.created_at', '>=', $req->input('filter_date_from'));
            }

            if ($req->filled('filter_date_to')) {
                $query->whereDate('a.created_at', '<=', $req->input('filter_date_to'));
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

                // Format subject type to short model name
                $subjectType = $value['subject_type'] ?? null;
                if ($subjectType) {
                    $shortName = class_basename($subjectType);
                    $dt['subject_type'] = '<span class="badge badge-light-info">' . $shortName . '</span>';
                } else {
                    $dt['subject_type'] = '-';
                }

                // Check if properties have old/attributes data for detail button
                $rawProps = $value['properties'] ?? null;
                $properties = is_string($rawProps) ? json_decode($rawProps, true) : (is_array($rawProps) ? $rawProps : []);
                $hasChanges = isset($properties['old']) || isset($properties['attributes']) || isset($properties['ip']);

                if ($hasChanges) {
                    $dt['detail'] = '<button type="button" class="btn btn-sm btn-light-primary btn-detail" data-id="' . $value['id'] . '"><i class="bi bi-eye"></i> Detail</button>';
                } else {
                    $dt['detail'] = '<button type="button" class="btn btn-sm btn-light-primary btn-detail" data-id="' . $value['id'] . '"><i class="bi bi-eye"></i> Detail</button>';
                }

                $resp[] = $dt;
            }

            $data['data'] = $resp;
            return response()->json($data);
        } elseif ($param1 == 'detail') {
            validate_and_response(['id' => ['Parameter data', 'required']]);

            $tableName = config('activitylog.table_name', 'sys_activity_log');
            $activity = DB::table($tableName . ' as a')
                ->selectRaw('a.*, u.name as causer_name')
                ->leftJoin('users as u', 'a.causer_id', '=', 'u.id')
                ->where('a.id', $req->input('id'))
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
                'created_at' => date('d M Y H:i:s', strtotime($activity->created_at)),
                'properties' => $properties,
                'old' => $properties['old'] ?? null,
                'attributes' => $properties['attributes'] ?? null,
                'ip' => $properties['ip'] ?? null,
                'user_agent' => $properties['user_agent'] ?? null,
                'provider' => $properties['provider'] ?? null,
            ];

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $result]);
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}

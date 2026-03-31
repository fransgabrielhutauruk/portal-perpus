@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools></x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card mb-5" data-cue="slideInLeft" data-duration="1000" data-delay="0">
            <div class="card-header border-0 pt-6 pb-0">
                <div class="card-title">
                    <h4 class="fw-bold mb-0"><i class="ki-outline ki-filter fs-3 me-2"></i> Filter</h4>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="true">
                        <i class="ki-outline ki-minus fs-3" id="filterToggleIcon"></i>
                    </button>
                </div>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body pt-4 pb-5">
                    <div class="row g-4">
                        <div class="col-md-2">
                            <label class="form-label fs-7 fw-semibold">User</label>
                            <select id="filter_user" class="form-select form-select-sm" data-control="select2"
                                data-placeholder="Semua User" data-allow-clear="true">
                                <option value="">Semua User</option>
                                @foreach ($pageData->users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fs-7 fw-semibold">Aksi</label>
                            <select id="filter_event" class="form-select form-select-sm" data-control="select2"
                                data-placeholder="Semua Aksi" data-allow-clear="true">
                                <option value="">Semua Aksi</option>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                                <option value="created">Tambah Data</option>
                                <option value="updated">Ubah Data</option>
                                <option value="deleted">Hapus Data</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fs-7 fw-semibold">Dari Tanggal</label>
                            <input type="date" id="filter_date_from" class="form-control form-control-sm" />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fs-7 fw-semibold">Sampai Tanggal</label>
                            <input type="date" id="filter_date_to" class="form-control form-control-sm" />
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="button" class="btn btn-sm btn-primary w-100" id="btnFilter">
                                <i class="ki-outline ki-filter-search fs-4 me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-light w-100" id="btnReset">
                                <i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="100">
            <div class="col-md-12">
                <x-table.dttable :builder="$pageData->dataTable" :responsive="false" draw_callback="" jf-data="activity-log"
                    jf-list="datatable" class="align-middle">
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modal-detail" type="centered" size="lg" title="Detail Log Aktivitas" :action="true">
        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Waktu:</div>
            <div class="col-md-9" id="detail-created-at"></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 fw-bold">User:</div>
            <div class="col-md-9">
                <div id="detail-user"></div>
                <small class="text-muted" id="detail-user-email"></small>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Aktivitas:</div>
            <div class="col-md-9" id="detail-description"></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Subjek:</div>
            <div class="col-md-9" id="detail-subject"></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Properties:</div>
            <div class="col-md-9">
                <pre id="detail-properties" class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"></pre>
            </div>
        </div>
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        let dataTableInstance = null;

        $(document).ready(function() {
            let tableId = '{{ $pageData->dataTable->getTableId() }}';
            dataTableInstance = $('#' + tableId).DataTable();

            dataTableInstance.settings()[0].ajax.data = function(d) {
                d.filter_user = $('#filter_user').val() || '';
                d.filter_event = $('#filter_event').val() || '';
                d.filter_date_from = $('#filter_date_from').val() || '';
                d.filter_date_to = $('#filter_date_to').val() || '';
            };

            $('#btnFilter').on('click', function() {
                dataTableInstance.ajax.reload(null, false);
            });

            $('#btnReset').on('click', function() {
                $('#filter_user').val('').trigger('change');
                $('#filter_event').val('').trigger('change');
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                dataTableInstance.ajax.reload(null, false);
            });

            $('#filterCollapse').on('show.bs.collapse', function() {
                $('#filterToggleIcon').removeClass('ki-plus').addClass('ki-minus');
            }).on('hide.bs.collapse', function() {
                $('#filterToggleIcon').removeClass('ki-minus').addClass('ki-plus');
            });

            $(document).on('click', '.btn-detail', function() {
                let id = parseInt($(this).data('id'), 10) || 0;
                if (!id) {
                    return;
                }

                viewDetail(id);
            });
        });

        function viewDetail(id) {
            $.ajax({
                url: `{{ route('app.activity-log.data') }}/detail`,
                type: 'GET',
                data: {
                    id: id
                },
                success: function(res) {
                    if (res.status) {
                        let data = res.data;

                        $('#detail-created-at').text(data.created_at || '-');
                        $('#detail-user').text(data.causer_name || 'System');
                        $('#detail-user-email').text(data.user_email || '');
                        $('#detail-description').text(data.description || '-');
                        $('#detail-subject').text((data.subject_type || '-') + ' #' + (data.subject_id || '-'));

                        if (data.properties && Object.keys(data.properties).length > 0) {
                            $('#detail-properties').text(JSON.stringify(data.properties, null, 2));
                        } else {
                            $('#detail-properties').text('Tidak ada properties');
                        }

                        $('#modal-detail').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat detail log'
                    });
                }
            });
        }
    </script>
@endpush

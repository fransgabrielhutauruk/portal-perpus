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

    <x-modal id="modalDetail" type="centered" :static="false" size="lg" title="Detail Activity Log">
        <div id="detailContent">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-semibold text-muted w-120px">Waktu</td>
                            <td>: <span id="detail_created_at"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">User</td>
                            <td>: <span id="detail_causer_name"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Modul</td>
                            <td>: <span id="detail_log_name"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-semibold text-muted w-120px">Aktivitas</td>
                            <td>: <span id="detail_description"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Subject</td>
                            <td>: <span id="detail_subject"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">IP Address</td>
                            <td>: <span id="detail_ip"></span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="authInfoSection" class="d-none mb-4">
                <div class="separator separator-dashed my-3"></div>
                <h6 class="fw-bold text-gray-700 mb-3"><i class="ki-outline ki-shield-tick fs-4 me-2"></i> Informasi
                    Autentikasi</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold text-muted w-120px">User Agent</td>
                        <td>: <span id="detail_user_agent" class="fs-8"></span></td>
                    </tr>
                    <tr id="providerRow" class="d-none">
                        <td class="fw-semibold text-muted">Provider</td>
                        <td>: <span id="detail_provider"></span></td>
                    </tr>
                </table>
            </div>

            <div id="changesSection" class="d-none">
                <div class="separator separator-dashed my-3"></div>
                <h6 class="fw-bold text-gray-700 mb-3"><i class="ki-outline ki-document fs-4 me-2"></i> Detail Perubahan
                    Data</h6>
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-200 align-middle gs-3 gy-2">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-3 rounded-start">Field</th>
                                <th>Nilai Lama</th>
                                <th class="pe-3 rounded-end">Nilai Baru</th>
                            </tr>
                        </thead>
                        <tbody id="changesTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        let dataTableInstance = null;
        let baseAjaxUrl = `{{ route('app.activity-log.data') }}/list`;

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
                dataTableInstance.ajax.reload();
            });

            $('#btnReset').on('click', function() {
                $('#filter_user').val('').trigger('change');
                $('#filter_event').val('').trigger('change');
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                dataTableInstance.ajax.reload();
            });

            $('#filter_date_from, #filter_date_to').on('change', function() {
                dataTableInstance.ajax.reload();
            });

            $('#filterCollapse').on('show.bs.collapse', function() {
                $('#filterToggleIcon').removeClass('ki-plus').addClass('ki-minus');
            }).on('hide.bs.collapse', function() {
                $('#filterToggleIcon').removeClass('ki-minus').addClass('ki-plus');
            });

            $(document).on('click', '.btn-detail', function() {
                let id = $(this).data('id');
                loadDetail(id);
            });
        });

        function loadDetail(id) {
            $.ajax({
                url: `{{ route('app.activity-log.data') }}/detail`,
                type: 'POST',
                data: {
                    id: id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#changesSection').addClass('d-none');
                    $('#authInfoSection').addClass('d-none');
                    $('#providerRow').addClass('d-none');
                    $('#changesTableBody').html('');
                },
                success: function(res) {
                    if (res.status) {
                        let data = res.data;

                        $('#detail_created_at').text(data.created_at || '-');
                        $('#detail_causer_name').text(data.causer_name || 'System');
                        $('#detail_log_name').text(data.log_name ? data.log_name.charAt(0).toUpperCase() + data
                            .log_name.slice(1) : '-');
                        $('#detail_description').text(data.description || '-');
                        $('#detail_subject').text(data.subject_type ? data.subject_type + (data.subject_id ?
                            ' #' + data.subject_id : '') : '-');
                        $('#detail_ip').text(data.ip || '-');

                        if (data.user_agent) {
                            $('#authInfoSection').removeClass('d-none');
                            $('#detail_user_agent').text(data.user_agent);

                            if (data.provider) {
                                $('#providerRow').removeClass('d-none');
                                $('#detail_provider').text(data.provider.charAt(0).toUpperCase() + data.provider
                                    .slice(1));
                            }
                        }

                        if (data.old || data.attributes) {
                            $('#changesSection').removeClass('d-none');
                            let tbody = '';
                            let allFields = {};

                            if (data.old) {
                                Object.keys(data.old).forEach(function(key) {
                                    allFields[key] = allFields[key] || {};
                                    allFields[key].old = data.old[key];
                                });
                            }
                            if (data.attributes) {
                                Object.keys(data.attributes).forEach(function(key) {
                                    allFields[key] = allFields[key] || {};
                                    allFields[key].new = data.attributes[key];
                                });
                            }

                            Object.keys(allFields).forEach(function(field) {
                                let oldVal = allFields[field].old !== undefined && allFields[field]
                                    .old !== null ? allFields[field].old :
                                    '<span class="text-muted fst-italic">-</span>';
                                let newVal = allFields[field].new !== undefined && allFields[field]
                                    .new !== null ? allFields[field].new :
                                    '<span class="text-muted fst-italic">-</span>';

                                let oldStr = typeof oldVal === 'object' ? JSON.stringify(oldVal) :
                                    String(oldVal);
                                let newStr = typeof newVal === 'object' ? JSON.stringify(newVal) :
                                    String(newVal);

                                let isChanged = oldStr !== newStr;
                                let highlightClass = isChanged ? 'bg-light-warning' : '';

                                tbody += `<tr class="${highlightClass}">
                                    <td class="ps-3 fw-semibold text-gray-700">${formatFieldName(field)}</td>
                                    <td><span class="text-danger">${escapeHtml(oldStr)}</span></td>
                                    <td class="pe-3"><span class="text-success">${escapeHtml(newStr)}</span></td>
                                </tr>`;
                            });

                            if (tbody === '') {
                                tbody =
                                    '<tr><td colspan="3" class="text-center text-muted py-4">Tidak ada detail perubahan</td></tr>';
                            }

                            $('#changesTableBody').html(tbody);
                        }

                        $('#modalDetail').modal('show');
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Gagal memuat detail';
                    Swal.fire('Error', msg, 'error');
                }
            });
        }

        function formatFieldName(field) {
            return field.replace(/_/g, ' ').replace(/\b\w/g, function(l) {
                return l.toUpperCase();
            });
        }

        function escapeHtml(text) {
            if (text === '-' || text.includes('fst-italic')) return text;
            let div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    </script>
@endpush

@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')

@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Dashboard</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="row g-5 g-xl-8">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-outline ki-folder fs-2x text-primary"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-7">Total Request</span>
                                <span
                                    class="text-gray-800 fw-bold fs-2x">{{ number_format($pageData->stats['totalRequests']) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="d-flex justify-content-between text-gray-600 fs-7">
                            <span>Semua Jenis</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-outline ki-book fs-2x text-success"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-7">Request Buku</span>
                                <span
                                    class="text-gray-800 fw-bold fs-2x">{{ number_format($pageData->stats['reqBuku']) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="d-flex justify-content-between text-gray-600 fs-7">
                            <span>Usulan Buku</span>
                            <a href="{{ route('app.usulan.index') }}" class="text-hover-primary">Lihat <i
                                    class="ki-outline ki-arrow-right fs-8"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-outline ki-teacher fs-2x text-info"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-7">Request Modul</span>
                                <span
                                    class="text-gray-800 fw-bold fs-2x">{{ number_format($pageData->stats['reqModul']) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="d-flex justify-content-between text-gray-600 fs-7">
                            <span>Modul Pembelajaran</span>
                            <a href="{{ route('app.usulan-modul.index') }}" class="text-hover-primary">Lihat <i
                                    class="ki-outline ki-arrow-right fs-8"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-outline ki-verify fs-2x text-warning"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-7">Bebas Pustaka</span>
                                <span
                                    class="text-gray-800 fw-bold fs-2x">{{ number_format($pageData->stats['reqBebasPustaka']) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="d-flex justify-content-between text-gray-600 fs-7">
                            <span>Surat Keterangan</span>
                            <a href="{{ route('app.req-bebas-pustaka.index') }}" class="text-hover-primary">Lihat <i
                                    class="ki-outline ki-arrow-right fs-8"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-danger">
                                    <i class="ki-outline ki-shield-tick fs-2x text-danger"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <span class="text-gray-600 fw-semibold d-block fs-7">Cek Plagiarisme</span>
                                <span
                                    class="text-gray-800 fw-bold fs-2x">{{ number_format($pageData->stats['reqTurnitin']) }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="d-flex justify-content-between text-gray-600 fs-7">
                            <span>Cek Turnitin</span>
                            <a href="{{ route('app.req-turnitin.index') }}" class="text-hover-primary">Lihat <i
                                    class="ki-outline ki-arrow-right fs-8"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

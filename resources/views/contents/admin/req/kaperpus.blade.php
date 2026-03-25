@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        @include('contents.admin.req.tabs')
        <div class="row">
            <div class="col-md">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="kaperpus"
                    jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="kaperpus">
                            <i class="bi bi-plus fs-2"></i> Tambah Kaperpus
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="kaperpus" title="Data Kaperpus">
        <form id="formData" class="needs-validation" jf-form="kaperpus" enctype="multipart/form-data">
            <input type="hidden" name="kaperpus_id" value="">
            <x-form.input type="text" class="mb-2" name="nama_kaperpus" label="Nama Kepala Perpustakaan" required />
            <div class="mb-2">
                <label class="form-label">Tanda Tangan (Gambar) <span class="text-danger">*</span></label>
                <a href="javascript:;"
                    class="d-flex rounded border-2 border-dashed border-gray-300 w-100 min-h-200px my-2 justify-content-center align-items-center position-relative"
                    id="ttdContent">
                    <img id="img-ttd-preview" src="" class="w-auto h-100 rounded d-none" alt="Preview TTD">
                    <span
                        class="text-gray-600 p-2 px-3 bg-gray-100 bg-opacity-20 rounded position-absolute top-50 start-50 translate-middle">
                        <i class="fa-solid fa-camera me-2"></i>Upload Tanda Tangan
                    </span>
                </a>
                <x-form.input type="file" label="" name="ttd_kaperpus" value="" class="d-none" />
                <div class="form-text">Format: JPG, PNG, JPEG | Maksimal: 2 MB</div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="kaperpus" />
        @endslot
    </x-modal>

    <x-modal id="modalCropTtd" type="centered" :static="true" size="lg" title="Pilih Tanda Tangan">
        <form class="needs-validation">
            <div class="form-group mb-2">
                <div class="input-group mb-2 mb-md-0">
                    <input type="file" class="form-control" id="image-input-ttd" accept="image/*">
                    <span class="input-group-text fs-8 fw-bold">
                        JPG,PNG | MAX 2 Mb
                    </span>
                </div>
            </div>

            <div class="form-group mb-2">
                <div class="d-flex w-100 h-450px border rounded border-gray-300 justify-content-center">
                    <img id="image-ttd" class="h-100 w-auto">
                </div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-set-ttd" text="Set TTD" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        var cropperTtd;
        var imageTtd = document.getElementById('image-ttd');
        var imageInputTtd = document.getElementById('image-input-ttd');

        function resetTtdState() {
            $('#img-ttd-preview').attr('src', '').addClass('d-none');
            const fileInput = document.getElementsByName('ttd_kaperpus')[0];
            if (fileInput) {
                fileInput.value = '';
            }
        }

        jForm.init({
            name: "kaperpus",
            base_url: `{{ route('app.req-bebas-pustaka.index') }}`,
            param: 'kaperpus',
            useFormData: true,
            onEdit: function(data) {
                if (data && data.ttd_kaperpus) {
                    $('#img-ttd-preview')
                        .attr('src', '/uploads/ttd_kaperpus/' + data.ttd_kaperpus)
                        .removeClass('d-none');
                } else {
                    $('#img-ttd-preview').attr('src', '').addClass('d-none');
                }
            }
        });

        $('#modalCropTtd').on('hidden.bs.modal', function() {
            if (cropperTtd) {
                cropperTtd.destroy();
                cropperTtd = null;
            }
            $('#image-input-ttd').val('');
            imageTtd.src = '';
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            resetTtdState();
        });

        $(document).on('click', '[jf-add="kaperpus"]', function() {
            resetTtdState();
        });

        $(document).on('click', '#ttdContent', function() {
            if (cropperTtd) {
                cropperTtd.destroy();
            }
            $('#image-input-ttd').val('');
            imageTtd.src = '';
            $('#modalCropTtd').modal('show');
        });

        imageInputTtd.addEventListener('change', function(e) {
            var files = e.target.files;
            var done = function(url) {
                if (cropperTtd) {
                    cropperTtd.destroy();
                }

                imageTtd.src = url;
                cropperTtd = new Cropper(imageTtd, {
                    cropBoxResizable: true,
                    cropBoxMovable: true,
                    dragMode: 'move',
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };

            var file;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    var reader = new FileReader();
                    reader.onload = function(evt) {
                        done(evt.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $(document).on('click', '.act-set-ttd', function() {
            if (cropperTtd) {
                var dataURL = cropperTtd.getCroppedCanvas().toDataURL('image/png');

                $('#img-ttd-preview').attr('src', dataURL).removeClass('d-none');

                cropperTtd.getCroppedCanvas().toBlob(function(blob) {
                    var file = new File([blob], 'ttd_kaperpus.png', {
                        type: 'image/png'
                    });

                    var fileInput = document.getElementsByName('ttd_kaperpus')[0];
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    $('#modalCropTtd').modal('hide');
                }, 'image/png');
            } else {
                $('#modalCropTtd').modal('hide');
            }
        });

        $(document).on('click', '[jf-set-active]', function(e) {
            e.preventDefault();
            var id = $(this).attr('jf-set-active');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Jadikan kaperpus ini sebagai yang aktif?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    ajaxRequest({
                        link: `{{ route('app.req-bebas-pustaka.show', ['param1' => 'kaperpus']) }}/set-active`,
                        data: { kaperpus_id: id },
                        swal_success: true,
                        callback: function() {
                            $('table[jf-data="kaperpus"]').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        });
    </script>
@endpush

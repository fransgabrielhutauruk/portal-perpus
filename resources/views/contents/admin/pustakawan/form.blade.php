@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.pustakawan.index') }}"></x-theme.back>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData"
            action="{{ $pageData->dataPustakawan['id'] ? route('app.pustakawan.update') : route('app.pustakawan.store') }}"
            class="needs-validation" jf-form="pustakawan">
            <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <div class="col-md-12">
                    <x-card>
                        <x-form.input class="mb-2" type="hidden" name="id"
                            value="{{ $pageData->dataPustakawan['id'] }}"></x-form.input>
                        
                        <x-form.input class="mb-2" type="text" label="Nama Pustakawan" name="nama" 
                            value="{{ $pageData->dataPustakawan['nama'] }}" required></x-form.input>
                        
                        <x-form.input class="mb-2" type="email" label="Email" name="email" 
                            value="{{ $pageData->dataPustakawan['email'] }}" required></x-form.input>
                        
                        <div class="mb-2">
                            <label class="form-label">Foto Pustakawan</label>
                            <a href="javascript:;"
                                class="d-flex rounded border-2 border-dashed border-gray-300 w-100 min-h-300px my-2 justify-content-center align-items-center position-relative"
                                id="fotoContent">
                                @if ($pageData->dataPustakawan['foto_path'])
                                    <img src="{{ $pageData->dataPustakawan['foto_path'] }}" class="w-auto h-100 rounded">
                                @endif
                                <span
                                    class="text-gray-600 p-2 px-3 bg-gray-100 bg-opacity-20 rounded position-absolute top-50 start-50 translate-middle">
                                    <i class="fa-solid fa-camera me-2"></i>Upload Foto
                                </span>
                            </a>
                            <x-form.input type="file" label="" name="upload_foto" value="" class="d-none"></x-form.input>
                            <div class="form-text">Format: JPG, PNG, JPEG | Maksimal: 2 MB | Ratio 3:4</div>
                        </div>
                        
                        <div class="mt-6">
                            <x-btn.form action="save" class="w-100" jf-save="pustakawan"></x-btn.form>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="lg" title="Pilih Foto Pustakawan">
        <form class="needs-validation">
            <div class="form-group mb-2">
                <div class="input-group mb-2 mb-md-0">
                    <input type="file" class="form-control" id="image-input" accept="image/*">
                    <span class="input-group-text fs-8 fw-bold">
                        JPG,PNG | MAX 2 Mb
                    </span>
                </div>
            </div>

            <div class="form-group mb-2">
                <div class="d-flex w-100 h-450px border rounded border-gray-300 justify-content-center">
                    <img id="image" class="h-100 w-auto">
                </div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" text="Set Foto" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        var cropper;
        var image = document.getElementById('image');
        var imageInput = document.getElementById('image-input');

        jForm.init({
            name: "pustakawan",
            base_url: `{{ route('app.pustakawan.index') }}`,
            onSave: function(data) {
                if (data) {
                    window.location.href = "{{ route('app.pustakawan.index') }}";
                }
            }
        })

        $('#modalForm').on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            $('#image-input').val('');
            image.src = '';
        });

        $('#modalForm').on('shown.bs.modal', function() {
            $('#image-input').focus();
        });

        $(document).on('click', '#fotoContent', function() {
            if (cropper) {
                cropper.destroy();
            }
            $('[id="image-input"]').val('')
            image.src = '';
            $('#modalForm').modal('show')
        })

        imageInput.addEventListener('change', function(e) {
            var files = e.target.files;
            var done = function(url) {
                if (cropper) {
                    cropper.destroy();
                }

                image.src = url;

                cropper = new Cropper(image, {
                    aspectRatio: 3 / 4,
                    preview: '.preview',
                    cropBoxResizable: true,
                    cropBoxMovable: true,
                    dragMode: 'move',
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };

            var reader;
            var file;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $(document).on('click', '.act-save', function() {
            if (cropper) {
                dataURL = cropper.getCroppedCanvas().toDataURL('image/jpeg');

                $('#fotoContent > img').remove()
                $('#fotoContent').append(`<img src="${dataURL}" class="w-auto h-100 rounded">`)

                cropper.getCroppedCanvas().toBlob(function(blob) {
                    var file = new File([blob], 'foto.jpg', {
                        type: 'image/jpeg'
                    });

                    var fileInput = document.getElementsByName('upload_foto')[0];
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    $('#modalForm').modal('hide');

                    setTimeout(() => {
                        $('.act-save').blur();
                        $('body').focus();
                    }, 100);
                }, 'image/jpeg');
            } else {
                $('#modalForm').modal('hide');
            }
        })
    </script>
@endpush

@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.berita.index') }}"></x-theme.back>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData"
            action="{{ $pageData->dataBerita['id'] ? route('app.berita.update') : route('app.berita.store') }}"
            class="needs-validation" jf-form="berita">
            <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <div class="col-md-8">
                    <x-card>
                        <x-form.input class="mb-2" type="hidden" name="id"
                            value="{{ $pageData->dataBerita['id'] }}"></x-form.input>
                        <x-form.textarea class="mb-2" type="text" label="Judul Berita" name="judul_berita" required>
                            {{ $pageData->dataBerita['judul_berita'] }}
                        </x-form.textarea>
                        <x-form.textarea class="mb-2" data-tinymce="advance" rows="5" label="Isi Berita"
                            name="isi_berita" value="">{{ $pageData->dataBerita['isi_berita'] }}</x-form.textarea>
                    </x-card>
                </div>
                <div class="col-md-4">
                    <x-card>
                        <x-form.select class="mb-2" label="Status" name="status_berita" value="" :search="false"
                            required>
                            <option value="draft"
                                {{ $pageData->dataBerita['status_berita'] == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="published"
                                {{ $pageData->dataBerita['status_berita'] == 'published' ? 'selected' : '' }}>
                                Published</option>
                            <option value="archived"
                                {{ $pageData->dataBerita['status_berita'] == 'archived' ? 'selected' : '' }}>
                                Archived</option>
                        </x-form.select>
                        <x-form.input class="mb-2" type="datetime-local" label="Tanggal Berita" name="tanggal_berita"
                            value="{{ $pageData->dataBerita['tanggal_berita'] ? $pageData->dataBerita['tanggal_berita'] : '' }}"></x-form.input>
                        <a href="javascript:;"
                            class="d-flex rounded border-2 border-dashed border-gray-300 w-100 min-h-200px my-2 justify-content-center align-items-center position-relative"
                            id="coverContent">
                            @if ($pageData->dataBerita['media_cover'])
                                <img src="{{ $pageData->dataBerita['media_cover'] }}" class="w-100 h-auto rounded">
                            @endif
                            <span
                                class="text-gray-600 p-2 px-3 bg-gray-100 bg-opacity-20 rounded position-absolute top-50 start-50 translate-middle">Gambar
                                Cover</span>
                        </a>
                        <x-form.input class="mb-2" type="file" label="" name="upload_file" value=""
                            class="d-none"></x-form.input>
                        <x-form.input class="mb-2" type="text" label="Kata Kunci Pencarian" name="meta_keyword_berita"
                            value="{{ $pageData->dataBerita['meta_keyword_berita'] }}"></x-form.input>
                        <x-form.textarea class="mb-2" rows="5" maxlength="255" label="Ringkasan Pencarian"
                            name="meta_desc_berita"
                            value="">{{ $pageData->dataBerita['meta_desc_berita'] }}</x-form.textarea>
                        <x-btn.form action="save" class="w-100 mt-6" jf-save="berita"></x-btn.form>
                    </x-card>
                </div>
            </div>
        </form>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="lg" title="Pilih Cover Berita">
        <form class="needs-validation">
            <div class="form-group mb-2">
                <div class="input-group mb-2 mb-md-0">
                    <input type="file" class="form-control" id="image-input" accept="image/*">
                    <span class="input-group-text fs-8 fw-bold">
                        JPG,PNG | MAX 5 Mb
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
            <x-btn.form action="save" class="act-save" text="Set Cover" />
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
            name: "berita",
            base_url: `{{ route('app.berita.index') }}`,
            onSave: function(data) {
                if (data) {
                    window.location.href = "{{ route('app.berita.index') }}";
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
        $(document).on('click', '#coverContent', function() {
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
                    aspectRatio: 930 / 520,
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

                $('#coverContent > img').remove()
                $('#coverContent').append(`<img src="${dataURL}" class="w-100 h-auto rounded">`)

                cropper.getCroppedCanvas().toBlob(function(blob) {
                    var file = new File([blob], 'cover.jpg', {
                        type: 'image/jpeg'
                    });

                    var fileInput = document.getElementsByName('upload_file')[0];
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

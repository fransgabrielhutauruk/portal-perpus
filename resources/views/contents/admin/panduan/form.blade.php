@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.panduan.index') }}"></x-theme.back>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData"
            action="{{ $pageData->dataPanduan['id'] ? route('app.panduan.update') : route('app.panduan.store') }}"
            class="needs-validation" jf-form="panduan">
            <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <div class="col-md-12">
                    <x-card>
                        <x-form.input class="mb-2" type="hidden" name="id"
                            value="{{ $pageData->dataPanduan['id'] }}"></x-form.input>
                        
                        <x-form.input class="mb-2" type="text" label="Judul Panduan" name="judul" 
                            value="{{ $pageData->dataPanduan['judul'] }}" required></x-form.input>
                        
                        <x-form.textarea class="mb-2" rows="5" label="Deskripsi" name="deskripsi">{{ $pageData->dataPanduan['deskripsi'] }}</x-form.textarea>
                        
                        <div class="mb-2">
                            <label class="form-label">File Panduan (PDF)</label>
                            <input type="file" class="form-control" name="upload_file" accept=".pdf">
                            <div class="form-text">Format: PDF | Maksimal: 10 MB</div>
                            
                            @if ($pageData->dataPanduan['file_path'])
                                <div class="mt-3">
                                    <a href="{{ $pageData->dataPanduan['file_path'] }}" target="_blank" class="btn btn-sm btn-light-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-6">
                            <x-btn.form action="save" class="w-100" jf-save="panduan"></x-btn.form>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "panduan",
            base_url: `{{ route('app.panduan.index') }}`,
            onSave: function(data) {
                if (data) {
                    window.location.href = "{{ route('app.panduan.index') }}";
                }
            }
        })
    </script>
@endpush

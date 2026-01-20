@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.faq.index') }}"></x-theme.back>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData"
            action="{{ $pageData->dataFaq['id'] ? route('app.faq.update') : route('app.faq.store') }}"
            class="needs-validation" jf-form="faq">
            <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <div class="col-md-12">
                    <x-card>
                        <x-form.input class="mb-2" type="hidden" name="id"
                            value="{{ $pageData->dataFaq['id'] }}"></x-form.input>
                        
                        <x-form.textarea class="mb-2" rows="3" label="Pertanyaan" name="pertanyaan" required>{{ $pageData->dataFaq['pertanyaan'] }}</x-form.textarea>
                        
                        <x-form.textarea class="mb-2" rows="6" label="Jawaban" name="jawaban" required>{{ $pageData->dataFaq['jawaban'] }}</x-form.textarea>
                        
                        <div class="mt-6">
                            <x-btn.form action="save" class="w-100" jf-save="faq"></x-btn.form>
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
            name: "faq",
            base_url: `{{ route('app.faq.index') }}`,
            onSave: function(data) {
                if (data) {
                    window.location.href = "{{ route('app.faq.index') }}";
                }
            }
        })
    </script>
@endpush

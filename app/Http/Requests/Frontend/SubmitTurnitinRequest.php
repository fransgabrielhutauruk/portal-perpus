<?php

namespace App\Http\Requests\Frontend;

use App\Enums\FrontendVerificationPurpose;
use App\Services\Frontend\GoogleVerificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitTurnitinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !empty($this->resolveVerifiedEmail());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $email = $this->resolveVerifiedEmail();

        return [
            'verification_token' => 'nullable|string',
            'nama_dosen'     => 'required|string|max:255',
            'inisial_dosen'  => 'required|string|max:10',
            'email_dosen'    => [
                'required',
                'email',
                Rule::in(array_filter([$email])),
            ],
            'nip'            => 'required|numeric',
            'prodi_id'       => 'required|numeric',
            'jenis_dokumen'  => 'required|in:Karya Ilmiah,Proyek Akhir',
            'judul_dokumen'  => 'required|string|max:255',
            'file_dokumen'   => 'required|file|mimes:pdf,doc,docx|max:10240',
            'keterangan'     => 'required|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_dosen'     => 'Nama Dosen',
            'inisial_dosen'  => 'Inisial',
            'email_dosen'    => 'Email',
            'nip'            => 'NIP',
            'prodi_id'       => 'Program Studi',
            'jenis_dokumen'  => 'Jenis Dokumen',
            'judul_dokumen'  => 'Judul Dokumen',
            'file_dokumen'   => 'File Dokumen',
            'keterangan'     => 'Keterangan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required'                 => ':attribute wajib diisi.',
            'email'                    => 'Format :attribute tidak valid.',
            'email_dosen.in'           => ':attribute tidak sesuai dengan akun login.',
            'numeric'                  => ':attribute harus berupa angka.',
            'digits'                   => ':attribute harus :digits digit.',
            'boolean'                  => ':attribute tidak valid.',
            'file'                     => ':attribute harus berupa file.',
            'mimes'                    => ':attribute harus berformat :values.',
            'min'                      => ':attribute minimal :min item.',
            'max'                      => ':attribute maksimal :max digit.',
        ];
    }

    protected function resolveVerifiedEmail(): ?string
    {
        return app(GoogleVerificationService::class)->resolveVerifiedEmailForSubmit(
            $this->input('verification_token'),
            FrontendVerificationPurpose::REQ_TURNITIN->value
        );
    }
}

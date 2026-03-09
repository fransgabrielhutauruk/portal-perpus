<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SubmitBebasPustakaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_mahasiswa'      => 'required|string|max:255',
            'email_mahasiswa'     => 'required|email',
            'nim'                 => 'required|string',
            'prodi_id'            => 'required|numeric',
            'link_kp_repository'  => 'required|url',
            'link_pa_repository'  => 'required|url',
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
            'nama_mahasiswa'      => 'Nama Mahasiswa',
            'email_mahasiswa'     => 'Email',
            'nim'                 => 'NIM',
            'prodi_id'            => 'Program Studi',
            'link_kp_repository'  => 'Link Repository KP',
            'link_pa_repository'  => 'Link Repository PA',
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
            'nama_mahasiswa.required'      => 'Nama mahasiswa wajib diisi.',
            'email_mahasiswa.required'     => 'Email wajib diisi.',
            'email_mahasiswa.email'        => 'Format email tidak valid.',
            'nim.required'                 => 'NIM wajib diisi.',
            'prodi_id.required'            => 'Program studi wajib dipilih.',
            'link_kp_repository.required'  => 'Link repository KP wajib diisi.',
            'link_kp_repository.url'       => 'Format link repository KP tidak valid.',
            'link_pa_repository.required'  => 'Link repository PA wajib diisi.',
            'link_pa_repository.url'       => 'Format link repository PA tidak valid.',
        ];
    }
}

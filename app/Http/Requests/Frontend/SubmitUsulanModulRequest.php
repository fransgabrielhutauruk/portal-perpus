<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SubmitUsulanModulRequest extends FormRequest
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
            'nama_dosen'           => 'required|string|max:255',
            'inisial_dosen'        => 'required|string|max:10',
            'email_dosen'          => 'required|email',
            'nip'                  => 'required|numeric',
            'prodi_id'             => 'required|numeric',
            'nama_mata_kuliah'     => 'required|string|max:255',
            'judul_modul'          => 'required|string|max:255',
            'penulis_modul'        => 'required|string',
            'tahun_modul'          => 'required|numeric|digits:4',
            'praktikum'            => 'required|boolean',
            'jumlah_dibutuhkan'    => 'nullable|numeric',
            'deskripsi_kebutuhan'  => 'nullable|string',
            'file'                 => 'nullable|file|mimes:pdf,doc,docx|max:20480',
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
            'nama_dosen'           => 'Nama Dosen',
            'inisial_dosen'        => 'Inisial Dosen',
            'email_dosen'          => 'Email Dosen',
            'nip'                  => 'NIP',
            'prodi_id'             => 'Program Studi',
            'nama_mata_kuliah'     => 'Nama Mata Kuliah',
            'judul_modul'          => 'Judul Modul',
            'penulis_modul'        => 'Penulis Modul',
            'tahun_modul'          => 'Tahun Modul',
            'praktikum'            => 'Jenis Modul',
            'jumlah_dibutuhkan'    => 'Jumlah Dibutuhkan',
            'deskripsi_kebutuhan'  => 'Deskripsi Kebutuhan',
            'file'                 => 'File',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required'  => ':attribute wajib diisi.',
            'email'     => 'Format :attribute tidak valid.',
            'numeric'   => ':attribute harus berupa angka.',
            'digits'    => ':attribute harus :digits digit.',
            'boolean'   => ':attribute tidak valid.',
            'file'      => ':attribute harus berupa file.',
            'mimes'     => ':attribute harus berformat :values.',
            'max'       => ':attribute maksimal :max.',
        ];
    }
}

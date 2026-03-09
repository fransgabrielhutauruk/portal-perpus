<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class SubmitUsulanBukuRequest extends FormRequest
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
            'nama_req'       => 'required|string|max:255',
            'email_req'      => 'required|email',
            'prodi_id'       => 'required|numeric',
            'nim'            => 'nullable|required_without:nip|numeric',
            'nip'            => 'nullable|required_without:nim|numeric',
            'judul_buku'     => 'required|string|max:255',
            'penulis_buku'   => 'required|string',
            'tahun_terbit'   => 'required|numeric|digits:4',
            'penerbit'       => 'nullable|array',
            'penerbit.*'     => 'nullable|string',
            'jenis_buku'     => 'required|array|min:1',
            'jenis_buku.*'   => 'string',
            'bahasa_buku'    => 'required|in:indonesia,inggris',
            'estimasi_harga' => 'nullable|numeric',
            'link_pembelian' => 'required',
            'alasan_usulan'  => 'required|string',
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
            'nama_req'       => 'Nama Lengkap',
            'email_req'      => 'Email PCR',
            'prodi_id'       => 'Program Studi',
            'nim'            => 'NIM',
            'nip'            => 'NIP',
            'judul_buku'     => 'Judul Buku',
            'penulis_buku'   => 'Penulis',
            'tahun_terbit'   => 'Tahun Terbit',
            'penerbit'       => 'Penerbit',
            'jenis_buku'     => 'Jenis Buku',
            'bahasa_buku'    => 'Bahasa Buku',
            'estimasi_harga' => 'Estimasi Harga',
            'link_pembelian' => 'Link Pembelian',
            'alasan_usulan'  => 'Alasan Usulan',
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
            'required'          => ':attribute wajib diisi.',
            'required_without'  => ':attribute wajib diisi jika :values tidak diisi.',
            'email'             => 'Format :attribute tidak valid.',
            'numeric'           => ':attribute harus berupa angka.',
            'digits'            => ':attribute harus :digits digit.',
            'array'             => ':attribute harus berupa pilihan.',
            'min'               => ':attribute minimal :min item.',
            'in'                => ':attribute tidak valid.',
        ];
    }
}

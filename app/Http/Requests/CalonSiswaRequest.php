<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CalonSiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // CalonSiswaRequest
    return [
        'NISN' => 'required|string|unique:pendaftarans,NISN',
        'nama' => 'required|string',
        'alamat' => 'required|string',
        'tgl_lahir' => 'required|date',
        'tmp_lahir' => 'required|string',
        'jenis_kelamin' => 'required|in:L,P',
        'agama' => 'required|string',
        'asal_sekolah' => 'required|string',
        'nama_ortu' => 'required|string',
        'pekerjaan_ortu' => 'required|string',
        'no_telp_ortu' => 'required|string',
        'foto' => 'nullable|image|max:2048',
        'tahun_ajaran' => 'required|string',
        'jurusan_id' => 'required|exists:jurusans,id',
        'nilai_semester_1' => 'nullable|numeric|between:0,100',
        'nilai_semester_2' => 'nullable|numeric|between:0,100',
        'nilai_semester_3' => 'nullable|numeric|between:0,100',
        'nilai_semester_4' => 'nullable|numeric|between:0,100',
        'nilai_semester_5' => 'nullable|numeric|between:0,100',
    ];
    }

    public function messages()
    {
        return [
            'NISN.required' => 'NISN wajib diisi',
            'NISN.unique' => 'NISN sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB',
            // tambahkan pesan error lainnya sesuai kebutuhan
        ];
    }

}

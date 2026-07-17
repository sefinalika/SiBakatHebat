<?php

namespace App\Http\Requests;

use App\Support\Wilayah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Data anak didik yang akan dites (diisi oleh guru / wali murid).
 */
class AnakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'min:2', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['laki-laki', 'perempuan'])],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'provinsi' => ['required', 'string', Rule::in(Wilayah::provinsiList())],
            'kota' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama anak wajib diisi.',
            'nama.min' => 'Nama anak minimal 2 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'provinsi.in' => 'Provinsi tidak valid.',
            'kota.required' => 'Kota/kabupaten wajib dipilih.',
        ];
    }

    /**
     * Pastikan kota termasuk dalam provinsi terpilih.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $provinsi = $this->input('provinsi');
                $kota = $this->input('kota');

                if ($provinsi && $kota && ! Wilayah::kotaValid($provinsi, $kota)) {
                    $validator->errors()->add('kota', 'Kota tidak sesuai dengan provinsi yang dipilih.');
                }
            },
        ];
    }
}

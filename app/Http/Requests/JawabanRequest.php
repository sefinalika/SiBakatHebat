<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JawabanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mengharapkan input berbentuk array `jawaban` dengan key 1-76:
     * jawaban[1]=8, jawaban[2]=5, ... jawaban[76]=10
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'jawaban' => ['required', 'array', 'size:76'],
        ];

        for ($i = 1; $i <= 76; $i++) {
            $rules["jawaban.{$i}"] = ['required', 'integer', 'min:1', 'max:10'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jawaban.required' => 'Jawaban soal wajib diisi.',
            'jawaban.size' => 'Seluruh 76 soal wajib dijawab.',
            'jawaban.*.required' => 'Setiap soal wajib dijawab.',
            'jawaban.*.integer' => 'Nilai jawaban harus berupa angka.',
            'jawaban.*.min' => 'Nilai jawaban minimal 1.',
            'jawaban.*.max' => 'Nilai jawaban maksimal 10.',
        ];
    }
}

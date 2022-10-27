<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PertanyaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bagian_id' => 'required',
            'pertanyaan' => 'required',
            'pertanyaan_urutan' => 'required',
            'pertanyaan_jenis_jawaban' => 'required',
            'required' => 'required|boolean',
            'lainnya' => 'boolean'
        ];
    }
}

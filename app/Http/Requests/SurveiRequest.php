<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class SurveiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function __construct(\Illuminate\Http\Request $request)
    {
        $is_wajib = false;
        if ($request->is_wajib == 1)
            $is_wajib = true;
        $request->merge([
            'is_wajib' => $is_wajib,
            'survei_oleh' => Auth::user()->id,
        ]);
    }

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
            //
            'survei_nama' => 'required',
            'survei_oleh' => 'required',
            'survei_deskripsi' => 'string',
            'survei_untuk' => 'required',
            'survei_status' => 'boolean',
            'is_wajib' => 'boolean',
            'is_aktif' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status' => false,
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}

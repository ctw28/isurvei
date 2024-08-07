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
        $is_sia = false;
        if ($request->is_sia == 1)
            $is_sia = true;
        $is_multiple = false;
        if ($request->is_multiple == 1)
            $is_multiple = true;
        $request->merge([
            'is_wajib' => $is_wajib,
            'is_sia' => $is_sia,
            'is_multiple' => $is_multiple,
            'organisasi_id' => Auth::user()->adminOrganisasi->organisasi_id,
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
            'organisasi_id' => 'required',
            'survei_deskripsi' => 'string',
            'survei_untuk' => 'required',
            'survei_status' => 'boolean',
            'is_wajib' => 'boolean',
            'is_aktif' => 'boolean',
            'is_sia' => 'boolean',
            'is_multiple' => 'boolean',
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

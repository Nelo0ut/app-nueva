<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentoRequest extends FormRequest
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
            //'user_id' => 'required',
            'tipodocumento_id' => 'required',
            // 'name' => 'required|regex:[A-Za-z1-9 ]',
            'name' => 'required',
            'entidad_id' => 'required',
            //'flestado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'tipodocumento_id.required' => 'Debe escoger el tipo de documento',
            'name.required'  => 'Ingresar el nombre de documento',
            'entidad_id.required'  => 'Debe escoger al menos un banco',
        ];
    }
}

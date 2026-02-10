<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntidadRequest extends FormRequest
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
        // return [
        //     // 'code' => 'required|regex:[1-9]',
        //     'code' => 'required|regex:/^[0-9]+$/',
        //     'name' => 'required',
        //     'alias' => 'required',
        // ];

        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name' => 'required',
                        'code' => 'required|unique:entidads,code,null,null,flestado,1',
                        'alias' => 'required',
                    ];
                }
            case 'PUT': {
                    return [
                        'name' => 'required',
                        // 'code' => 'required|regex:/^[0-9]+$/|unique:entidads,code,' . $this->id . ',id,flestado,1',
                        'alias' => 'required',
                    ];
                }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'No has ingresado el nombre de la entidad',
            'code.required' => 'No has ingresado el código de la entidad',
            'alias.required' => 'No has ingresado el alias de la entidad',

            'code.regex' => 'El código de la entidad solo debe ser números',
            'code.unique' => 'El código de la entidad ya está en uso',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTarifaOrdinariaRequest extends FormRequest
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
            'tipo' => 'required|integer|min:1|max:2',
            'entidad_des' => 'required|different:entidad_par',
            'entidad_par' => 'required|different:entidad_des',
            'plaza' => 'required|integer|min:1|max:3',
            'moneda' => 'required|integer|min:1|max:2',
            'monto' => 'required|numeric',
        ];
    }
}

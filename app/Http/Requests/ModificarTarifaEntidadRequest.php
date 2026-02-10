<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificarTarifaEntidadRequest extends FormRequest
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
            'sol_montofijomin' => 'required|numeric|min:0|max:99999',
            'dol_montofijomin' => 'required|numeric|min:0|max:99999',
            
            'sol_porcentaje' => 'required|numeric|min:0|max:99999',
            'dol_porcentaje' => 'required|numeric|min:0|max:99999',
            'sol_montofijomax' => 'required|numeric|min:0|max:99999',
            'dol_montofijomax' => 'required|numeric|min:0|max:99999',
            
        ];
    }
       
}

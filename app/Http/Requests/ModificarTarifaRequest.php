<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificarTarifaRequest extends FormRequest
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
            'sol_porcentaje' => 'required|numeric|min:0|max:99999',
            'sol_montofijomin' => 'required|numeric|min:0|max:99999',
            'sol_montofijomax' => 'required|numeric|min:0|max:99999',
            'dol_porcentaje' => 'required|numeric|min:0|max:99999',
            'dol_montofijomin' => 'required|numeric|min:0|max:99999',
            'dol_montofijomax' => 'required|numeric|min:0|max:99999'
        ];
    }

    public function messages()
    {
        return [
            'sol_porcentaje.required' => 'No has ingresado el PEN %',
            'sol_montofijomin.required' => 'No has ingresado el PEN min',
            'sol_montofijomax.required' => 'No has ingresado el PEN max',
            'dol_porcentaje.required' => 'No has ingresado el USD %',
            'dol_montofijomin.required' => 'No has ingresado el USD min',
            'dol_montofijomax.required' => 'No has ingresado el USD max',
            'sol_porcentaje.numeric' => 'No has ingresado un PEN % válido',
            'sol_montofijomin.numeric' => 'No has ingresado el PEN min válido',
            'sol_montofijomax.numeric' => 'No has ingresado el PEN max válido',
            'dol_porcentaje.numeric' => 'No has ingresado el USD % válido',
            'dol_montofijomin.numeric' => 'No has ingresado el USD min válido',
            'dol_montofijomax.numeric' => 'No has ingresado el USD max válido',
            'sol_porcentaje.min' => 'No has ingresado un PEN % válido',
            'sol_montofijomin.min' => 'No has ingresado el PEN min válido',
            'sol_montofijomax.min' => 'No has ingresado el PEN max válido',
            'dol_porcentaje.min' => 'No has ingresado el USD % válido',
            'dol_montofijomin.min' => 'No has ingresado el USD min válido',
            'dol_montofijomax.min' => 'No has ingresado el USD max válido'            
        ];
    }    
}

      

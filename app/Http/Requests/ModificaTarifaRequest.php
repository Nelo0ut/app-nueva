<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificaTarifaRequest extends FormRequest
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
            'sol_int_montofijo' => 'required|numeric|min:0|max:99999',
            'sol_int_porcentaje' => 'required|numeric|min:0|max:99999',
            'sol_int_montofijomax' => 'required|numeric|min:0|max:99999',
            'sol_int_montofijomin' => 'required|numeric|min:0|max:99999',
            'sol_cci_porcentaje' => 'required|numeric|min:0|max:99999',
            'sol_cci_montofijomax' => 'required|numeric|min:0|max:99999',
            'sol_cci_montofijomin' => 'required|numeric|min:0|max:99999',
            'sol_cci_montofijo' => 'required|numeric|min:0|max:99999',
            'dol_int_montofijo' => 'required|numeric|min:0|max:99999',
            'dol_int_porcentaje' => 'required|numeric|min:0|max:99999',
            'dol_int_montofijomax' => 'required|numeric|min:0|max:99999',
            'dol_int_montofijomin' => 'required|numeric|min:0|max:99999',
            'dol_cci_porcentaje' => 'required|numeric|min:0|max:99999',
            'dol_cci_montofijomax' => 'required|numeric|min:0|max:99999',
            'dol_cci_montofijomin' => 'required|numeric|min:0|max:99999',
            'dol_cci_montofijo' => 'required|numeric|min:0|max:99999',
        ];
    }
}

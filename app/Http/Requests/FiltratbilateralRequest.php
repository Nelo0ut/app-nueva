<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltratbilateralRequest extends FormRequest
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
            'entidad1_id' => 'required|different:entidad2_id',
            'entidad2_id' => 'required|different:entidad1_id',
            'tipotarifas' => 'required',
            
        ];
    }
    public function messages()
    {
        return [
            'entidad1_id.required' => 'Campo entidad 1 es obligatorio',
            'entidad2_id.required' => 'Campo entidad 2 es obligatorio',
            'entidad1_id.different' => 'Campo entidad 1 debe ser distinto a la entidad 2',
            'entidad2_id.different' => 'Campo entidad 2 debe ser distinto a la entidad 1',
            'tipotarifas.required'  => 'Campo tipo tarifas es obligatorio',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OficinaRequest extends FormRequest
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
            'name' => 'required|max:35',
            'entidad_id' => 'required',
            'tipooficina_id' => 'required',
            'numero' => 'required|max:3',
            'domicilio' => 'required|max:80',
            'localidad' => 'required|max:35',
            'nameplaza' => 'required|max:35',
            'numeroplaza' => 'required|max:4',
            'preftelefono' => 'max:3',
            'telefono1' => 'max:8',
            'telefono2' => 'max:8',
            'fax' => 'max:8',
            'centraltelefonica' => 'max:8',
        ];
    }

    public function messages()
    {
        return [
            'entidad_id.required' => 'Debe escoger un banco',
            'name.required'  => 'Ingresar el nombre de la oficina',
            'tipooficina_id.required'  => 'Debe escoger un tipo de oficina',
            'numero.required'  => 'Debe ingresar el número de oficina',
            'domicilio.required'  => 'Debe ingresar el domicilio de la oficina',
            'localidad.required'  => 'Debe ingresar la localidad de la oficina',
            'nameplaza.required'  => 'Debe ingresar el nombre de la plaza',
            'numeroplaza.required'  => 'Debe ingresar el número de la plaza',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // print_r($this->user->id);
        // exit();
        switch ($this->method()) {
            case 'POST':
            {
                return [
                    'name' => 'required',
                    'email' => 'required|unique:users,email,null,null,flestado,1',
                    'usuario' => 'required|unique:users,usuario,null,null,flestado,1',
                    'role_id' => 'required',
                    'entidad_id' => 'required'
                ];
            }
            case 'PUT':
            {
                return [
                    'name' => 'required',
                    // 'email' => 'required|unique:users,email,'.$this->id. ',id,flestado,1',
                    // 'usuario' => 'required|unique:users,usuario'.$this->route('id'),
                    // 'role_id' => 'required',
                    'entidad_id' => 'required',
                ];
            }
            default: break;
        }
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearSolicitudRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'carrera_id' => 'required|exists:carreras,carrera_id'
        ];
    }
}

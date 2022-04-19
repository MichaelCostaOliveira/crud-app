<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpaRequest extends FormRequest
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
            'titulo' => 'required|min:3',
            'criticidade' => 'required',
            'tipo' => 'required',
            'status' => 'required',
            'descricao' => 'string|nullable',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'Nome é obrigatório!',
            'titulo.min' => 'Minímo de 3 caracteres para o Nome!',
            'criticidade.required' => 'Criticidade é obrigatório!',
            'tipo.required' => 'Tipo é obrigatório!',
            'status.required' => 'Status é obrigatório!'
        ];
    }
}

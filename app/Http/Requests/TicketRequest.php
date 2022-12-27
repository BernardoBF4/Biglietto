<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tic_title' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Este campo é obrigatório',
            'tic_title.max' => 'O número máximo de caracteres é 255',
        ];
    }
}

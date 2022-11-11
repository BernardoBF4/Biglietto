<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
      'status' => 'nullable|boolean',
      'title' => 'required|string|max:250',
      'start_datetime' => 'required|date|before:end_datetime',
      'end_datetime' => 'required|date|after:start_datetime',
    ];
  }

  public function messages()
  {
    return [
      '*.required' => 'Este campo é obirgatório.',
      'title.max' => 'O máximo de caracteres é 250.',
      '*.date' => 'Por favor, selecione uma data válida.',
      'start_datetime.before' => 'A data de início não pode ser maior que a data de término.',
      'end_datetime.after' => 'A data de início não pode ser maior que a data de término.',
    ];
  }
}

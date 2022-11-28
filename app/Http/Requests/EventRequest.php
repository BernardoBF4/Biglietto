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
      'eve_status' => 'nullable|boolean',
      'eve_title' => 'required|string|max:250',
      'eve_start_datetime' => 'required|date|before:eve_end_datetime',
      'eve_end_datetime' => 'required|date|after:eve_start_datetime',
    ];
  }

  public function messages()
  {
    return [
      '*.required' => 'Este campo é obirgatório.',
      'eve_title.max' => 'O máximo de caracteres é 250.',
      '*.date' => 'Por favor, selecione uma data válida.',
      'eve_start_datetime.before' => 'A data de início não pode ser maior que a data de término.',
      'eve_end_datetime.after' => 'A data de início não pode ser maior que a data de término.',
    ];
  }
}

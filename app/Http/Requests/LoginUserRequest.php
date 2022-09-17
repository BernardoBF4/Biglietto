<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
      'email' => 'required|email|exists:users',
      'password' => 'required',
    ];
  }

  public function messages()
  {
    return [
      '*.required' => 'Este campo é obirgatório.',
      'email.email' => 'Por favor, digite um e-mail válido.',
      'email.exists' => 'Este e-mail não existe em nosso sistema.',
    ];
  }
}

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
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'email' => 'required|string|email|unique:users,email',
      'name' => 'required|string|max:255',
      'password' => 'required|string|min:6|max:12',
      'password_confirmation' => 'required|string|min:6|max:12'
    ];
  }

  public function messages()
  {
    return [
      '*.required' => 'Este campo é obrigatório.',
      'email.email' => 'O e-mail precisa ser válido.',
      'email.unique' => 'Este e-mail já está sendo utilizado por algum usuário.',
      'name.max' => 'O nome pode ter no máximo 255 caracteres.',
      'password.min' => 'A senha precisa ter no mínimo 6 caracteres.',
      'password.max' => 'A senha pode ter no máximo 12 caracteres.',
      'password_confirmation.min' => 'A senha precisa ter no mínimo 6 caracteres.',
      'password_confirmation.max' => 'A senha pode ter no máximo 12 caracteres.'
    ];
  }
}

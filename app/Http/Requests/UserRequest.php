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
    if ($this->__isCreateAction()) {
      $rules = [
        'usu_email' => 'required|string|email|unique:users,usu_email',
        'usu_name' => 'required|string|max:255',
        'usu_password' => 'required|string|min:6|max:12|same:usu_password_confirmation',
        'usu_password_confirmation' => 'required|string|min:6|max:12|same:usu_password'
      ];
    } else {
      $rules = [
        'usu_email' => 'required|string|email|unique:users,usu_email',
        'usu_name' => 'required|string|max:255',
        'usu_password' => 'required_with:usu_password_confirmation|string|min:6|max:12|same:usu_password_confirmation',
        'usu_password_confirmation' => 'required_with:usu_password|string|min:6|max:12|same:usu_password'
      ];
    }

    return $rules;
  }

  public function messages()
  {
    return [
      '*.required' => 'Este campo é obrigatório.',
      'usu_email.email' => 'O e-mail precisa ser válido.',
      'usu_email.unique' => 'Este e-mail já está sendo utilizado por algum usuário.',
      'usu_name.max' => 'O nome pode ter no máximo 255 caracteres.',
      'usu_password.min' => 'A senha precisa ter no mínimo 6 caracteres.',
      'usu_password.max' => 'A senha pode ter no máximo 12 caracteres.',
      'usu_password.same' => 'A senha e confirmação de senha não são iguais.',
      'usu_password_confirmation.min' => 'A senha precisa ter no mínimo 6 caracteres.',
      'usu_password_confirmation.max' => 'A senha pode ter no máximo 12 caracteres.',
      'usu_password_confirmation.same' => 'A senha e confirmação de senha não são iguais.'
    ];
  }

  private function __isCreateAction()
  {
    return $this->isMethod('POST');
  }
}

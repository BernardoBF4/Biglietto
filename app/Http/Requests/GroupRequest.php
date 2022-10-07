<?php

namespace App\Http\Requests;

use App\Models\Modules;
use Illuminate\Foundation\Http\FormRequest;

use function PHPUnit\Framework\isInstanceOf;

class GroupRequest extends FormRequest
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
      'name' => 'string|max:100|required',
      'status' => 'boolean|nullable',
      'modules' => [
        'required',
        function ($attribute, $value, $fail) {
          foreach ($value as $module) {
            if (!($module instanceof Modules)) {
              $fail('Os módulos devem ser módulos válidos.');
            }
          }
        }
      ],
    ];
  }

  public function messages()
  {
    return [
      'name.max' => 'O nome pode ter 100 caracteres no máximo.',
      'name.required' => 'O nome precisa ser preenchido.',
      'modules.required' => 'O grupo precisa de pelo menos um módulo.'
    ];
  }
}

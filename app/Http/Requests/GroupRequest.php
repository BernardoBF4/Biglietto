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
      'gro_name' => 'string|max:100|required',
      'gro_status' => 'boolean|nullable',
      'modules' => [
        'required',
        fn ($attribute, $value, $fail) => $this->areModulesValidModules($attribute, $value, $fail),
      ],
    ];
  }

  public function messages()
  {
    return [
      'gro_name.max' => 'O nome pode ter 100 caracteres no máximo.',
      'gro_name.required' => 'O nome precisa ser preenchido.',
      'modules.required' => 'O grupo precisa de pelo menos um módulo.'
    ];
  }

  private function areModulesValidModules($attribute, $value, $fail)
  {
    foreach ($value as $module_id) {
      if (!Modules::where('id', $module_id)->count()) {
        $fail('Os módulos devem ser módulos válidos.');
      }
    }
  }
}

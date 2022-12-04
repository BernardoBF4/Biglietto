<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
  public function login(array $credentials)
  {
    try {
      $user = User::where('usu_email', $credentials['usu_email'])->first();

      $this->isUserAlreadyLogged();
      $this->isPasswordCorrect($user->usu_password, $credentials['usu_password']);
      auth()->login($user);

      return redirect()->to(route('cms.groups.index'));
    } catch (Throwable $th) {
      return redirect()->back()->with('response', cms_response($th->getMessage(), false, 400));
    }
  }

  private function isUserAlreadyLogged()
  {
    if (auth()->check()) {
      throw new Exception(__('auth.error.already_logged'));
    }
  }

  private function isPasswordCorrect($user_password, $typed_password)
  {
    if (!Hash::check($typed_password, $user_password)) {
      throw new Exception(__('auth.error.wrong_password'));
    }
  }
}

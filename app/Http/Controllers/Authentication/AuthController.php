<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Exception;
use App\Services\AuthService;

class AuthController extends Controller
{

  public function getUserTheLoginView()
  {
    return view('cms.auth.login');
  }

  public function loginUser(LoginUserRequest $request)
  {
    try {
      $data = $request->validated();
      $user = User::where('email', $data['email'])->first();
      $auth = new AuthService($user, $data);

      $auth->redirectUserIfAlreadyLoggedIn();
      $auth->redirectUserIfPasswordIsIncorrect();

      auth()->login($user);

      return redirect()->to(route('cms.groups.index'));
    } catch (Exception $e) {
      return redirect()->back()->with('message', $e->getMessage());
    }
  }
}

<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function getUserTheLoginView()
  {
    return view('cms.auth.login');
  }

  public function loginUser(LoginUserRequest $request)
  {
    $data = $request->validated();
    $user = User::where('email', $data['email'])->first();

    if (Hash::check($data['password'], $user->password)) {
      auth()->login($user);
      return redirect()->back()->with('message', 'Você está logado');
    }

    return redirect()->back()->withErrors('message', 'Você não está logado');
  }
}

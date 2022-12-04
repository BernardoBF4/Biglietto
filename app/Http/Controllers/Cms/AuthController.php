<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Exception;
use App\Services\AuthService;

class AuthController extends Controller
{
  protected AuthService $auth_service;

  public function __construct(AuthService $auth_service)
  {
    $this->auth_service = $auth_service;
  }

  public function getUserTheLoginView()
  {
    return view('cms.auth.login');
  }

  public function loginUser(LoginUserRequest $request)
  {
    $response = $this->auth_service->login($request->all());
    return $response;
  }
}

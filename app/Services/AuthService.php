<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  protected $user;
  protected $data;

  public function __construct(User $user, $data)
  {
    $this->user = $user;
    $this->data = $data;
  }

  public function redirectUserIfAlreadyLoggedIn()
  {
    if (auth()->check()) {
      throw new Exception('Você já está logado');
    }
  }

  public function redirectUserIfPasswordIsIncorrect()
  {
    if (!Hash::check($this->data['password'], $this->user->password)) {
      throw new Exception('A senha está incorreta.');
    }
  }
}

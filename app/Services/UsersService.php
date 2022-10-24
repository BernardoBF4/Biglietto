<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersService
{
  private ?User $user;
  private array $data;

  public function __construct(array $data, ?User $user)
  {
    $this->user = $user;
    $this->data = $data;
  }

  public function createNewUser()
  {
    try {
      $this->__checkIfPasswordsMatch();

      $this->data['token'] = Hash::make($this->data['email']);
      $this->data['password'] = Hash::make($this->data['password']);
      User::create($this->data);

      return ['msg' => trans('cms.users.success_create')];
    } catch (\Throwable $th) {
      return ['msg' => $th->getMessage()];
    }
  }

  private function __checkIfPasswordsMatch()
  {
    if ($this->data['password'] !== $this->data['password_confirmation']) {
      throw new Exception(trans('cms.users.error_passwords'));
    }
  }
}

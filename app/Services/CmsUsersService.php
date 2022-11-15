<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\CRUD;

class CmsUsersService implements CRUD
{
  private ?int $user_id;
  private ?array $data;
  private ?string $users_to_be_deleted;

  public function __construct(?array $data, ?int $user_id, ?string $users_to_be_deleted)
  {
    $this->user_id = $user_id;
    $this->data = $data;
    $this->users_to_be_deleted = $users_to_be_deleted;
  }

  public function create()
  {
    try {
      $this->data['token'] = Hash::make($this->data['email']);
      $this->data['password'] = Hash::make($this->data['password']);
      User::create($this->data);

      return cms_response(trans('cms.users.success_create'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function update()
  {
    try {
      if (array_key_exists('password', $this->data)) {
        $this->data['password'] = Hash::make($this->data['password']);
      }

      $user = $this->__findOrFail();
      $user->update($this->data);

      return cms_response(trans('cms.users.success_update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function delete()
  {
    User::whereIn('id', json_decode($this->users_to_be_deleted))->delete();
    return cms_response(trans('cms.users.success_delete'));
  }

  private function __findOrFail()
  {
    $user = User::find($this->user_id);
    if ($user instanceof User) {
      return $user;
    }
    throw new Exception(trans('cms.users.error_user_not_found'));
  }
}

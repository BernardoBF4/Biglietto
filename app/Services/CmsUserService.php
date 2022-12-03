<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\CRUD;

class CmsUserService implements CRUD
{
  public function create(array $data)
  {
    $data['usu_token'] = Hash::make($data['usu_email']);
    $data['usu_password'] = Hash::make($data['usu_password']);
    User::create($data);

    return cms_response(__('users.success.create'));
  }

  public function update(int $id, array $data)
  {
    try {
      if (array_key_exists('usu_password', $data)) {
        $data['usu_password'] = Hash::make($data['usu_password']);
      }

      $user = $this->__findOrFail($id);
      $user->update($data);

      return cms_response(__('users.success.update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function delete(string $ids)
  {
    User::whereIn('usu_id', json_decode($ids))->delete();
    return cms_response(__('users.success.delete'));
  }

  private function __findOrFail(int $id)
  {
    $user = User::find($id);
    if ($user instanceof User) {
      return $user;
    }
    throw new Exception(__('users.error.not_found'));
  }
}

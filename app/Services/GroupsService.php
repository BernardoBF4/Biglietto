<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupsService
{
  public function listAll()
  {
    $groups = Group::all();

    return $groups;
  }

  public function createGroupWith($data)
  {
    try {
      DB::beginTransaction();

      $modules_id = array_pop($data);

      $group = Group::create($data);
      $group->modules()->attach($modules_id);

      DB::commit();
      return ['msg' => 'Grupo criado com sucesso!'];
    } catch (\Throwable $error) {
      DB::rollBack();
      return ['msg' => $error->getMessage()];
    }
  }

  public function updateAGroupWith($data, $group)
  {
    try {
      DB::beginTransaction();

      $modules_ids = array_pop($data);

      $group->update($data);
      $group->modules()->sync($modules_ids);

      DB::commit();
      return ['msg' => 'O grupo foi atualziado com sucesso!'];
    } catch (\Throwable $error) {
      DB::rollBack();
      return ['msg' => $error->getMessage()];
    }
  }
}

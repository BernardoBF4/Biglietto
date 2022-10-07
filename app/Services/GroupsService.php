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

      $modules = array_pop($data);

      $group = Group::create($data);
      $group->modules()->attach($modules);

      DB::commit();
      return ['msg' => 'Grupo criado com sucesso!'];
    } catch (\Throwable $error) {
      DB::rollBack();
      return ['msg' => $error->getMessage()];
    }
  }
}

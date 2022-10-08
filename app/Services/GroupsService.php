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
      return ['msg' => trans('cms.groups.success_create')];
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
      return ['msg' => trans('cms.groups.success_update')];
    } catch (\Throwable $error) {
      DB::rollBack();
      return ['msg' => $error->getMessage()];
    }
  }

  public function deleteGroup($group_id)
  {
    try {
      DB::beginTransaction();

      $group = Group::where('id', $group_id)->first();

      if (isset($group)) {
        $group->delete();
      }

      DB::commit();
      return ['msg' => trans('cms.groups.success_delete')];
    } catch (\Throwable $error) {
      DB::rollBack();
      return ['msg' => $error->getMessage()];
    }
  }
}

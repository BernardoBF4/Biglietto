<?php

namespace App\Services;

use App\Models\Group;

class GroupsService
{
  public function listAll()
  {
    $groups = Group::all();

    return $groups;
  }

  public function createGroupWith($data)
  {
    $modules_id = array_pop($data);

    $group = Group::create($data);
    $group->modules()->attach($modules_id);
    return ['msg' => trans('cms.groups.success_create')];
  }

  public function updateAGroupWith($data, $group)
  {

    $modules_ids = array_pop($data);

    $group->update($data);
    $group->modules()->sync($modules_ids);

    return ['msg' => trans('cms.groups.success_update')];
  }


  public function deleteGroup($groups_id)
  {
    Group::whereIn('id', json_decode($groups_id))->delete();

    return ['msg' => trans('cms.groups.success_delete')];
  }
}

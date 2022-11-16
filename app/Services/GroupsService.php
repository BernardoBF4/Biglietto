<?php

namespace App\Services;

use App\Models\Group;
use App\Interfaces\CRUD;
use Exception;

class GroupsService implements CRUD
{
  public function listAll()
  {
    return Group::all();
  }

  public function create(array $data)
  {
    $modules_id = array_pop($data);

    $group = Group::create($data);
    $group->modules()->attach($modules_id);

    return cms_response(trans('cms.groups.success_create'));
  }

  public function update(int $id, array $data)
  {
    try {
      $modules_ids = $data['modules'];
      unset($data['modules']);
      $group = $this->__findOrFail($id);

      $group->update($data);
      $group->modules()->sync($modules_ids);

      return cms_response(trans('cms.groups.success_update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }


  public function delete(string $ids)
  {
    Group::whereIn('id', json_decode($ids))->delete();
    return cms_response(trans('cms.groups.success_delete'));
  }

  private function __findOrFail(int $id)
  {
    $group = Group::find($id);
    if ($group instanceof Group) {
      return $group;
    }
    throw new Exception(trans('cms.groups.error_not_found'));
  }
}

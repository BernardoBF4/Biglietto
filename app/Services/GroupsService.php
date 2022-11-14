<?php

namespace App\Services;

use App\Models\Group;
use App\Interfaces\CRUD;
use Exception;

class GroupsService implements CRUD
{
  private ?array $data;
  private ?int $group_id;
  private ?string $groups_to_be_deleted;

  public function __construct(?array $data, ?int $group_id, ?string $groups_to_be_deleted)
  {
    $this->data = $data;
    $this->group_id = $group_id;
    $this->groups_to_be_deleted = $groups_to_be_deleted;
  }

  public function listAll()
  {
    return Group::all();
  }

  public function create()
  {
    $modules_id = array_pop($this->data);

    $group = Group::create($this->data);
    $group->modules()->attach($modules_id);
    return cms_response(trans('cms.groups.success_create'));
  }

  public function update()
  {
    try {
      $modules_ids = array_pop($this->data);
      $group = $this->__findOrFail();

      $group->update($this->data);
      $group->modules()->sync($modules_ids);

      return cms_response(trans('cms.groups.success_update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }


  public function delete()
  {
    Group::whereIn('id', json_decode($this->groups_to_be_deleted))->delete();
    return cms_response(trans('cms.groups.success_delete'));
  }

  private function __findOrFail()
  {
    $group = Group::find($this->group_id);
    if ($group instanceof Group) {
      return $group;
    }
    throw new Exception(trans('cms.groups.error_not_found'));
  }
}

<?php

namespace App\Services;

use App\Models\Group;
use App\Interfaces\CRUD;
use Exception;

class GroupService implements CRUD
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

        return cms_response(__('group.success.create'));
    }

    public function update(int $id, array $data)
    {
        try {
            $modules_ids = $data['modules'];
            unset($data['modules']);
            $group = $this->__findOrFail($id);

            $group->update($data);
            $group->modules()->sync($modules_ids);

            return cms_response(__('group.success.update'));
        } catch (\Throwable $th) {
            return cms_response($th->getMessage(), false, 400);
        }
    }


    public function delete(string $ids)
    {
        $groups = Group::whereIn('gro_id', json_decode($ids))->get();

        foreach ($groups as $group) {
            $group->delete();
        }

        return cms_response(__('group.success.delete'));
    }

    private function __findOrFail(int $id)
    {
        $group = Group::find($id);
        if ($group instanceof Group) {
            return $group;
        }
        throw new Exception(__('group.error.not_found'));
    }
}

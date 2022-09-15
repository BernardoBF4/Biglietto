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
}

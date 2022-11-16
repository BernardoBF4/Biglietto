<?php

namespace App\Interfaces;

interface CRUD
{
  public function create(array $data);
  public function update(int $id, array $data);
  public function delete(string $ids);
};

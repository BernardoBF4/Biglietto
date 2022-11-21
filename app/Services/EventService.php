<?php

namespace App\Services;

use App\Models\Event;
use App\Interfaces\CRUD;
use Exception;

class EventService implements CRUD
{

  public function create(array $data)
  {
    Event::create($data);
    return cms_response(trans('cms.events.success_create'));
  }

  public function update(int $id, array $data)
  {
    try {
      $event = $this->__findOrFail($id);
      $event->update($data);

      return cms_response(trans('cms.events.success_update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function delete(string $ids)
  {
    Event::whereIn('id', json_decode($ids))->delete();
    return cms_response(trans('cms.events.success_delete'));
  }

  private function __findOrFail(int $id)
  {
    $event = Event::find($id);
    if ($event instanceof Event) {
      return $event;
    }
    throw new Exception(trans('cms.events.error_not_found'));
  }
}

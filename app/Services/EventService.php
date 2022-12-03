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
    return cms_response(__('events.success.create'));
  }

  public function update(int $id, array $data)
  {
    try {
      $event = $this->__findOrFail($id);
      $event->update($data);

      return cms_response(__('events.success.update'));
    } catch (\Throwable $th) {
      return cms_response($th->getMessage(), false, 400);
    }
  }

  public function delete(string $ids)
  {
    Event::whereIn('eve_id', json_decode($ids))->delete();
    return cms_response(__('events.success.delete'));
  }

  private function __findOrFail(int $id)
  {
    $event = Event::find($id);
    if ($event instanceof Event) {
      return $event;
    }
    throw new Exception(__('events.error.not_found'));
  }
}

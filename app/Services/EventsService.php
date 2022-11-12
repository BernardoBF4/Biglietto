<?php

namespace App\Services;

use App\Models\Event;
use Exception;

class EventsService
{
  private array $data;
  private ?int $event_id;

  public function __construct(array $data, ?int $event_id)
  {
    $this->data = $data;
    $this->event_id = $event_id;
  }

  public function createEvent()
  {
    Event::create($this->data);
    return ['msg' => trans('cms.events.success_create')];
  }

  public function updateEvent()
  {
    try {
      $event = $this->__findEventOrFail();
      $event->update($this->data);
      return ['msg' => trans('cms.events.success_update')];
    } catch (\Throwable $th) {
      return ['msg' => $th->getMessage()];
    }
  }

  private function __findEventOrFail()
  {
    $event = Event::find($this->event_id);
    if ($event instanceof Event) {
      return $event;
    }
    throw new Exception(trans('cms.events.error_not_found'));
  }
}

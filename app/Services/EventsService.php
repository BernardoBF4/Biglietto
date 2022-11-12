<?php

namespace App\Services;

use App\Models\Event;
use Exception;

class EventsService
{
  private ?array $data;
  private ?int $event_id;
  private ?string $events_to_be_deleted;

  public function __construct(?array $data, ?int $event_id, ?string $events_to_be_deleted)
  {
    $this->data = $data;
    $this->event_id = $event_id;
    $this->events_to_be_deleted = $events_to_be_deleted;
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

  public function deleteEvent()
  {
    Event::whereIn('id', json_decode($this->events_to_be_deleted))->delete();

    return ['msg' => trans('cms.events.success_delete')];
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

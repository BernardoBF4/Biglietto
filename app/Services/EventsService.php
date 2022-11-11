<?php

namespace App\Services;

use App\Models\Event;

class EventsService
{
  private array $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function createEvent()
  {
    Event::create($this->data);
    return ['msg' => trans('cms.events.success_create')];
  }
}

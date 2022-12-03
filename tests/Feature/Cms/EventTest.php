<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /** @test */
  public function unauthenticated_users_are_redirected()
  {
    $this->withoutExceptionHandling();

    $response = $this->get(route('cms.events.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function an_event_can_be_created()
  {
    $this->withoutExceptionHandling()->signIn();

    $event_data = Event::factory()->make()->toArray();

    $response = $this->post(route('cms.events.store', $event_data));

    $response->assertSessionHas('response', cms_response(__('event.success.create')));
  }

  /** @test */
  public function when_an_event_is_created_its_data_is_persited_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $event_data = Event::factory()->make()->toArray();

    $this->post(route('cms.events.store', $event_data));

    $this->assertDatabaseHas('events', $event_data);
  }

  /** @test */
  public function an_event_cannot_be_created_with_end_date_being_smaller_than_start_date()
  {
    $this->signIn();

    $event_data = Event::factory()->withEndDateSmallerThanStartDate()->make()->toArray();

    $this->post(route('cms.events.store', $event_data));

    $this->checkIfSessionErrorMatchesString('eve_start_datetime', 'A data de início não pode ser maior que a data de término.');
    $this->checkIfSessionErrorMatchesString('eve_end_datetime', 'A data de início não pode ser maior que a data de término.');
  }

  /** @test */
  public function an_event_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->make()->toArray();

    $response = $this->patch(route('cms.events.update', $event->eve_id), $event_data);

    $response->assertSessionHas('response', cms_response(__('event.success.update')));
  }

  /** @test */
  public function when_an_event_is_updated_its_data_is_persisted_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $event = Event::factory()->create()->toArray();
    $event_data = Event::factory()->make()->toArray();

    $this->patch(route('cms.events.update', $event['eve_id']), $event_data);

    $this->assertDatabaseHas('events', $event_data);
    $this->assertDatabaseMissing('events', $event);
  }

  /** @test */
  public function an_event_cannot_be_updated_with_end_date_being_smaller_than_start_date()
  {
    $this->signIn();

    $event = Event::factory()->create()->toArray();
    $event_data = Event::factory()->withEndDateSmallerThanStartDate()->make()->toArray();

    $this->patch(route('cms.events.update', $event['eve_id']), $event_data);

    $this->checkIfSessionErrorMatchesString('eve_start_datetime', 'A data de início não pode ser maior que a data de término.');
    $this->checkIfSessionErrorMatchesString('eve_end_datetime', 'A data de início não pode ser maior que a data de término.');
  }

  /** @test */
  public function when_updating_an_event_if_not_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->make()->toArray();

    $response = $this->patch(route('cms.events.update', $event->eve_id + 1), $event_data);

    $response->assertSessionHas('response', cms_response(__('event.error.not_found'), false, 400));
  }

  /** @test */
  public function events_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $users_id = Event::factory(2)->create()->pluck('eve_id');

    $response = $this->delete(route('cms.events.destroy', $users_id));

    $response->assertSessionHas('response', cms_response(__('event.success.delete')));
  }

  /** @test */
  public function an_event_has_many_tickets()
  {
    $ticket = Ticket::factory()->create();

    $event = Event::where('eve_id', $ticket->event->eve_id)->first();

    $this->assertInstanceOf(Ticket::class, $event->tickets[0]);
  }
}

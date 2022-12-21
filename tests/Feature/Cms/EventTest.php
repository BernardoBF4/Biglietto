<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /** @test */
  public function unauthenticated_users_are_redirected()
  {
    $response = $this->get(route('cms.events.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function creating_an_event_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $event_data = Event::factory()->make()->toArray();

    $response = $this->post(route('cms.events.store'), $event_data);

    $response->assertSessionHas('response', cms_response(__('event.success.create')));
  }

  /** @test */
  public function creating_an_event_persists_its_data_to_the_database()
  {
    $this->signIn();

    $event_data = Event::factory()->make()->toArray();

    $this->post(route('cms.events.store'), $event_data);

    $this->assertDatabaseHas('events', $event_data);
  }

  /** @test */
  public function creating_an_event_with_start_date_bigger_than_end_date_puts_an_error_message_in_the_session()
  {
    $this->signIn();

    $event_data = Event::factory()->withEndDateSmallerThanStartDate()->make()->toArray();

    $this->post(route('cms.events.store'), $event_data);

    $this->checkIfSessionErrorMatchesString('eve_start_datetime', 'A data de início não pode ser maior que a data de término.');
    $this->checkIfSessionErrorMatchesString('eve_end_datetime', 'A data de início não pode ser maior que a data de término.');
  }

  /** @test */
  public function updating_an_event_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->make()->toArray();

    $response = $this->patch(route('cms.events.update', $event->eve_id), $event_data);

    $response->assertSessionHas('response', cms_response(__('event.success.update')));
  }

  /** @test */
  public function updating_an_event_persists_its_data_to_the_database_and_removes_the_old_data()
  {
    $this->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->make()->toArray();

    $this->patch(route('cms.events.update', $event->eve_id), $event_data);

    $this->assertDatabaseHas('events', $event_data);
    $this->assertDatabaseMissing('events', $event->toArray());
  }

  /** @test */
  public function updating_an_event_with_start_date_bigger_than_end_date_puts_an_error_message_in_the_session()
  {
    $this->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->withEndDateSmallerThanStartDate()->make()->toArray();

    $this->patch(route('cms.events.update', $event->eve_id), $event_data);

    $this->checkIfSessionErrorMatchesString('eve_start_datetime', 'A data de início não pode ser maior que a data de término.');
    $this->checkIfSessionErrorMatchesString('eve_end_datetime', 'A data de início não pode ser maior que a data de término.');
  }

  /** @test */
  public function updating_a_not_found_event_puts_an_error_on_the_session()
  {
    $this->signIn();

    $event = Event::factory()->create();
    $event_data = Event::factory()->make()->toArray();

    $response = $this->patch(route('cms.events.update', $event->eve_id + 1), $event_data);

    $response->assertSessionHas('response', cms_response(__('event.error.not_found'), false, 400));
  }

  /** @test */
  public function deleting_an_event_puts_a_success_message_on_the_database()
  {
    $this->signIn();

    $events_id = Event::factory(2)->create()->pluck('eve_id');

    $response = $this->delete(route('cms.events.destroy', ['event' => $events_id]));

    $response->assertSessionHas('response', cms_response(__('event.success.delete')));
  }

  /** @test */
  public function deleting_an_event_removes_its_data_from_the_database()
  {
    $this->signIn();

    $event = Event::factory()->create();

    $this->delete(route('cms.events.destroy', ['event' => $event->pluck('eve_id')]));

    $this->assertDatabaseMissing('events', $event->toArray());
  }

  /** @test */
  public function an_event_has_many_tickets()
  {
    $event = Event::factory()->has(Ticket::factory(2), 'tickets')->create();

    $this->assertInstanceOf(Ticket::class, $event->tickets[0]);
    $this->assertInstanceOf(Ticket::class, $event->tickets[1]);
  }
}

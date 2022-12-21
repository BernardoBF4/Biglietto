<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
use App\Models\Lot;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /** @test */
  public function unauthenticated_users_are_redirected()
  {
    $response = $this->get(route('cms.tickets.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function creating_a_ticket_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->post(route('cms.tickets.store', $ticket_data));

    $response->assertSessionHas('response', cms_response(__('ticket.success.create')));
  }

  /** @test */
  public function creating_a_ticket_persists_its_data_to_the_database()
  {
    $this->signIn();

    $ticket_data = Ticket::factory()->make()->toArray();

    $this->post(route('cms.tickets.store', $ticket_data));

    $this->assertDatabaseHas('tickets', $ticket_data);
  }

  /** @test */
  public function updating_a_ticket_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id]), $ticket_data);

    $response->assertSessionHas('response', cms_response(__('ticket.success.update')));
  }

  /** @test */
  public function updating_a_ticket_persists_its_data_to_the_database_and_removes_the_old_data()
  {
    $this->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id]), $ticket_data);

    $this->assertDatabaseHas('tickets', $ticket_data);
    $this->assertDatabaseMissing('tickets', $ticket->toArray());
  }

  /** @test */
  public function updating_a_not_found_ticket_puts_an_error_message_in_the_session()
  {
    $this->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id + 1]), $ticket_data);

    $response->assertSessionHas('response', cms_response(__('ticket.error.not_found'), false, 400));
  }

  /** @test */
  public function deleting_tickets_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $ticket_ids = Ticket::factory(2)->create()->pluck('tic_id');

    $response = $this->delete(route('cms.tickets.destroy', ['ticket' => $ticket_ids]));

    $response->assertSessionHas('response', cms_response(__('ticket.success.delete')));
  }

  /** @test */
  public function deleting_tickets_removes_their_data_from_the_database()
  {
    $this->signIn();

    $tickets = Ticket::factory()->create();

    $this->delete(route('cms.tickets.destroy', ['ticket' => $tickets->pluck('tic_id')]));

    $this->assertDatabaseMissing('tickets', $tickets->toArray());
  }

  /** @test */
  public function a_ticket_can_have_multiple_lots()
  {
    $ticket = Ticket::factory()->has(Lot::factory(2), 'lots')->create();

    $this->assertInstanceOf(Lot::class, $ticket->lots[0]);
    $this->assertInstanceOf(Lot::class, $ticket->lots[1]);
  }

  /** @test */
  public function a_ticket_belongs_to_an_event()
  {
    $ticket = Ticket::factory()->for(Event::factory(), 'event')->create();

    $this->assertInstanceOf(Event::class, $ticket->event);
  }
}

<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
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
    $this->withoutExceptionHandling();

    $response = $this->get(route('cms.tickets.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function a_ticket_can_be_created()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->post(route('cms.tickets.store', $ticket_data));

    $response->assertSessionHas('response', cms_response(__('ticket.success.create')));
  }

  /** @test */
  public function when_a_ticket_is_created_its_data_is_persisted_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket_data = Ticket::factory()->make()->toArray();

    $this->post(route('cms.tickets.store', $ticket_data));

    $this->assertDatabaseHas('tickets', $ticket_data);
  }

  /** @test */
  public function a_ticket_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id]), $ticket_data);

    $response->assertSessionHas('response', cms_response(__('ticket.success.update')));
  }

  /** @test */
  public function updating_a_ticket_persists_its_data_to_the_database_and_removes_the_old_data()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id]), $ticket_data);

    $this->assertDatabaseHas('tickets', $ticket_data);
    $this->assertDatabaseMissing('tickets', $ticket->toArray());
  }

  /** @test */
  public function when_updating_a_ticket_if_not_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket = Ticket::factory()->create();
    $ticket_data = Ticket::factory()->make()->toArray();

    $response = $this->patch(route('cms.tickets.update', ['ticket' => $ticket->tic_id + 1]), $ticket_data);

    $response->assertSessionHas('response', cms_response(__('ticket.error.not_found'), false, 400));
  }

  /** @test */
  public function multiple_tickets_can_be_deleted()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket_ids = Ticket::factory(2)->create()->pluck('tic_id');

    $response = $this->delete(route('cms.tickets.destroy', ['ticket' => $ticket_ids]));

    $response->assertSessionHas('response', cms_response(__('ticket.success.delete')));
  }
}

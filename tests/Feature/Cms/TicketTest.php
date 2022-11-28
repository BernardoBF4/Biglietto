<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
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

    $ticket_data = [
      'fk_events_id' => Event::factory()->create()->eve_id,
      'tic_title' => $this->faker->name(),
      'tic_price' => $this->faker->randomNumber(),
      'tic_status' => $this->faker->boolean(),
    ];

    $response = $this->post(route('cms.tickets.store', $ticket_data));

    $response->assertSessionHas('response', cms_response(trans('cms.ticket.success_create')));
  }

  /** @test */
  public function when_a_test_is_created_its_data_is_persisted_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $ticket_data = [
      'fk_events_id' => Event::factory()->create()->eve_id,
      'tic_title' => $this->faker->name(),
      'tic_price' => $this->faker->randomNumber(),
      'tic_status' => $this->faker->boolean(),
    ];

    $this->post(route('cms.tickets.store', $ticket_data));

    $this->assertDatabaseHas('tickets', $ticket_data);
  }
}

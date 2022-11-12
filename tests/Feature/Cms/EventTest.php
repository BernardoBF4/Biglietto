<?php

namespace Tests\Feature\Cms;

use App\Models\Event;
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

    $event_data = [
      'status' => $this->faker->boolean(),
      'title' => $this->faker->name(),
      'start_datetime' => Carbon::parse($this->faker->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse($this->faker->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('cms.events.store', $event_data));

    $response->assertSessionHas('message', trans('cms.events.success_create'));
  }

  /** @test */
  public function when_an_event_is_created_its_data_is_persited_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $event_data = [
      'status' => $this->faker->boolean(),
      'title' => $this->faker->name(),
      'start_datetime' => Carbon::parse($this->faker->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse($this->faker->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
    ];

    $this->post(route('cms.events.store', $event_data));

    $this->assertDatabaseHas('events', $event_data);
  }

  /** @test */
  public function an_event_cannot_be_created_with_end_date_being_smaller_than_start_date()
  {
    $this->signIn();

    $event_data = [
      'status' => $this->faker->boolean(),
      'title' => $this->faker->name(),
      'start_datetime' => Carbon::parse($this->faker->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse($this->faker->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
    ];

    $this->post(route('cms.events.store', $event_data));

    $this->checkIfSessionErrorMatchesString('start_datetime', 'A data de início não pode ser maior que a data de término.');
    $this->checkIfSessionErrorMatchesString('end_datetime', 'A data de início não pode ser maior que a data de término.');
  }

  /** @test */
  public function an_event_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $event_data = [
      'status' => $this->faker->boolean(),
      'title' => $this->faker->name(),
      'start_datetime' => Carbon::parse($this->faker->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse($this->faker->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
    ];
    $event = Event::factory()->create();

    $response = $this->patch(route('cms.events.update', $event->id), $event_data);

    $response->assertSessionHas('message', trans('cms.events.success_update'));
  }

  /** @test */
  public function when_updating_an_event_if_not_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $event_data = [
      'status' => $this->faker->boolean(),
      'title' => $this->faker->name(),
      'start_datetime' => Carbon::parse($this->faker->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse($this->faker->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
    ];
    $event = Event::factory()->create();

    $response = $this->patch(route('cms.events.update', $event->id + 1), $event_data);

    $response->assertSessionHas('message', trans('cms.events.error_not_found'));
  }
}

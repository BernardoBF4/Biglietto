<?php

namespace Tests\Feature\Cms;

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
  public function an_event_cannot_be_created_with_end_date_being_small_than_start_date()
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
}

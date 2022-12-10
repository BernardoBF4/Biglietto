<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
  public function definition()
  {
    return [
      'tic_title' => fake()->word(),
      'tic_price' => fake()->randomNumber(),
      'tic_status' => fake()->boolean(),
      'fk_events_id' => Event::factory()->create()->eve_id,
    ];
  }

  public function withEvent($event_id)
  {
    return $this->state(function () use ($event_id) {
      return [
        'fk_events_id' => $event_id
      ];
    });
  }
}

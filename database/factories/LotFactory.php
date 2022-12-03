<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lot>
 */
class LotFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'lot_status' => fake()->boolean(),
      'lot_price' => fake()->numberBetween(10, 100),
      'fk_tickets_id' => Ticket::factory()->create()->tic_id,
    ];
  }

  public function withTicket($ticket_id)
  {
    return $this->state(function () use ($ticket_id) {
      return [
        'fk_tickets_id' => $ticket_id,
      ];
    });
  }
}

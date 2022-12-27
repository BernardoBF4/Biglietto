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

    public function active()
    {
        return $this->state(function () {
            return [
                'tic_status' => true,
            ];
        });
    }
}

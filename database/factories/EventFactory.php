<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'id' => fake()->randomNumber(),
      'status' => fake()->boolean(),
      'title' => fake()->name(),
      'start_datetime' => Carbon::parse(fake()->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
      'end_datetime' => Carbon::parse(fake()->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
    ];
  }
}
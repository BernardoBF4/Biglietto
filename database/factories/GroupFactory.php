<?php

namespace Database\Factories;

use App\Models\Modules;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'gro_name' => fake()->word(),
      'gro_status' => fake()->boolean(),
    ];
  }

  public function withModule()
  {
    return $this->state(function () {
      return [
        'modules' => Modules::factory(1)->create()->pluck('id'),
      ];
    });
  }
}

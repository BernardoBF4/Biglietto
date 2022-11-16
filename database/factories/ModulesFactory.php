<?php

namespace Database\Factories;

use App\Models\Modules;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modules>
 */
class ModulesFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'father_id' => null,
      'name' => fake()->word(),
      'status' => fake()->boolean(),
    ];
  }
}

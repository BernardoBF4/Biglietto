<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->gro_id,
      'usu_email' => fake()->safeEmail(),
      'usu_name' => fake()->name(),
      'usu_token' => Hash::make(Str::random(10)),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   *
   * @return static
   */
  public function unverified()
  {
    return $this->state(fn (array $attributes) => [
      'usu_email_verified_at' => null,
    ]);
  }

  public function withPassword($password)
  {
    return $this->state(function () use ($password) {
      return [
        'usu_password' => bcrypt($password)
      ];
    });
  }
}

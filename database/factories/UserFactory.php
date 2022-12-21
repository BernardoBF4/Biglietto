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
      'usu_email' => fake()->safeEmail(),
      'usu_name' => fake()->name(),
      'fk_groups_id' => Group::factory()->hasAttached(Modules::factory(), [], 'modules')->create()->gro_id,
    ];
  }

  public function withPassword()
  {
    return $this->state(function () {
      return [
        'usu_password' => fake()->password(6, 12),
      ];
    });
  }

  public function withEncryptedPassword($password = null)
  {
    return $this->state(function () use ($password) {
      $password = $password ?? fake()->password(6, 12);
      return [
        'usu_password' => bcrypt($password),
      ];
    });
  }

  public function withPasswordAndConfirmation()
  {
    return $this->state(function () {
      $password = fake()->password(6, 12);
      return [
        'usu_password' => $password,
        'usu_password_confirmation' => $password,
      ];
    });
  }

  public function withMismatchingPasswords()
  {
    return $this->state(function () {
      return [
        'usu_password' => fake()->password(6, 12),
        'usu_password_confirmation' => fake()->password(6, 12),
      ];
    });
  }
}

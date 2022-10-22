<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{

  use RefreshDatabase, WithFaker;

  /** @test */
  public function a_user_is_redirected_if_not_authenticated()
  {
    $this->withoutExceptionHandling();

    $response = $this->get(route('cms.users.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function a_user_can_create_another_user()
  {
    $this->withoutExceptionHandling()->signIn();

    $password = $this->faker->password(6, 12);
    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
      'password' => $password,
      'password_confirmation' => $password,
    ];

    $response = $this->post(route('cms.users.store'), $user_data);

    $response->assertSessionHas('message', trans('cms.users.success_create'));
  }

  /** @test */
  public function a_user_receives_an_error_if_the_passwords_dont_match()
  {
    $this->withoutExceptionHandling()->signIn();

    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
      'password' => $this->faker->password(6, 12),
      'password_confirmation' => $this->faker->password(6, 12),
    ];

    $response = $this->post(route('cms.users.store'), $user_data);

    $response->assertSessionHas('message', trans('cms.users.error_passwords'));
  }
}

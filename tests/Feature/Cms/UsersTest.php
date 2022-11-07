<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use App\Models\User;
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
  public function a_user_receives_an_error_if_the_passwords_dont_match_when_creating_a_user()
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

  /** @test */
  public function a_user_can_update_another_user()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $password = $this->faker->password(6, 12);
    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
      'password' => $password,
      'password_confirmation' => $password,
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->id]), $user_data);

    $response->assertSessionHas('message', trans('cms.users.success_update'));
  }

  /** @test */
  public function the_user_receives_an_error_if_user_is_not_found()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::all()->last();
    $password = $this->faker->password(6, 12);
    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
      'password' => $password,
      'password_confirmation' => $password,
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->id + 1]), $user_data);

    $response->assertSessionHas('message', trans('cms.users.error_user_not_found'));
  }

  /** @test */
  public function the_user_can_update_a_user_without_updating_the_password()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->id]), $user_data);

    $response->assertSessionHas('message', trans('cms.users.success_update'));
  }

  /** @test */
  public function a_user_receives_an_error_if_the_passwords_dont_match_when_updating_a_user()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $user_data = [
      'email' => $this->faker->safeEmail(),
      'group' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'name' => $this->faker->name(),
      'password' => $this->faker->password(6, 12),
      'password_confirmation' => $this->faker->password(6, 12),
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->id]), $user_data);

    $response->assertSessionHas('message', trans('cms.users.error_passwords'));
  }

  /** @test */
  public function users_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $users_id = User::factory(2)->withPassword($this->faker->password(6, 12))->create()->pluck('id');

    $response = $this->delete(route('cms.users.destroy', $users_id));

    $response->assertSessionHas('message', trans('cms.users.success_delete'));
  }
}

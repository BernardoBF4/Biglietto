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

  use WithFaker, RefreshDatabase;

  /** @test */
  public function unauthenticated_users_are_redirected()
  {
    $this->withoutExceptionHandling();

    $response = $this->get(route('cms.users.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function a_user_can_be_created()
  {
    $this->withoutExceptionHandling()->signIn();

    $password = $this->faker->password(6, 12);
    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->gro_id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
      'usu_password' => $password,
      'usu_password_confirmation' => $password,
    ];

    $response = $this->post(route('cms.users.store'), $user_data);

    $response->assertSessionHas('response', cms_response(trans('cms.users.success_create')));
  }

  /** @test */
  public function if_passwords_dont_match_when_creating_a_user_an_error_is_returned()
  {
    $this->signIn();

    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->gro_id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
      'usu_password' => $this->faker->password(6, 12),
      'usu_password_confirmation' => $this->faker->password(6, 12),
    ];

    $this->post(route('cms.users.store'), $user_data);

    $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
    $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
  }

  /** @test */
  public function a_user_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $password = $this->faker->password(6, 12);
    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->gro_id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
      'usu_password' => $password,
      'usu_password_confirmation' => $password,
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $response->assertSessionHas('response', cms_response(trans('cms.users.success_update')));
  }

  /** @test */
  public function if_the_user_isnt_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::all()->last();
    $password = $this->faker->password(6, 12);
    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
      'usu_password' => $password,
      'usu_password_confirmation' => $password,
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id + 1]), $user_data);

    $response->assertSessionHas('response', cms_response(trans('cms.users.error_user_not_found'), false, 400));
  }

  /** @test */
  public function a_user_can_be_updated_without_updating_its_password()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->gro_id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
    ];

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $response->assertSessionHas('response', cms_response(trans('cms.users.success_update')));
  }

  /** @test */
  public function if_passwords_dont_match_when_updating_a_user_an_error_is_returned()
  {
    $this->signIn();

    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();
    $user_data = [
      'fk_groups_id' => Group::factory()->has(Modules::factory(), 'modules')->create()->id,
      'usu_email' => $this->faker->safeEmail(),
      'usu_name' => $this->faker->name(),
      'usu_password' => $this->faker->password(6, 12),
      'usu_password_confirmation' => $this->faker->password(6, 12),
    ];

    $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
    $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
  }

  /** @test */
  public function users_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $users_id = User::factory(2)->withPassword($this->faker->password(6, 12))->create()->pluck('usu_id');

    $response = $this->delete(route('cms.users.destroy', $users_id));

    $response->assertSessionHas('response', cms_response(trans('cms.users.success_delete')));
  }
}

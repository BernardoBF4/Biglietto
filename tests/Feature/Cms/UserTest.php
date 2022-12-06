<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
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

    $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

    $response = $this->post(route('cms.users.store'), $user_data);

    $response->assertSessionHas('response', cms_response(__('user.success.create')));
  }

  /** @test */
  public function if_passwords_dont_match_when_creating_a_user_an_error_is_returned()
  {
    $this->signIn();

    $user_data = User::factory()->withMismatchingPasswords()->make()->toArray();

    $this->post(route('cms.users.store'), $user_data);

    $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
    $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
  }

  /** @test */
  public function a_user_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withEncryptedPassword()->create();
    $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $response->assertSessionHas('response', cms_response(__('user.success.update')));
  }

  /** @test */
  public function if_the_user_isnt_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withEncryptedPassword()->create();
    $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id + 1]), $user_data);

    $response->assertSessionHas('response', cms_response(__('user.error.not_found'), false, 400));
  }

  /** @test */
  public function a_user_can_be_updated_without_updating_its_password()
  {
    $this->withoutExceptionHandling()->signIn();

    $user = User::factory()->withEncryptedPassword()->create();
    $user_data = User::factory()->make()->toArray();

    $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $response->assertSessionHas('response', cms_response(__('user.success.update')));
  }

  /** @test */
  public function if_passwords_dont_match_when_updating_a_user_an_error_is_returned()
  {
    $this->signIn();

    $user = User::factory()->withEncryptedPassword()->create();
    $user_data = User::factory()->withMismatchingPasswords()->make()->toArray();

    $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

    $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
    $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
  }

  /** @test */
  public function users_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $users_id = User::factory(2)->withEncryptedPassword()->create()->pluck('usu_id');

    $response = $this->delete(route('cms.users.destroy', $users_id));

    $response->assertSessionHas('response', cms_response(__('user.success.delete')));
  }

  /** @test */
  public function a_user_belongs_to_a_group()
  {
    $user = User::factory()->withEncryptedPassword()->create();

    $this->assertInstanceOf(Group::class, $user->group);
  }
}

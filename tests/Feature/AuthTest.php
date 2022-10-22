<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @test */
  public function a_user_can_access_the_login_page()
  {
    $this->withoutExceptionHandling();

    $this->get('/cms/login')->assertStatus(200);
  }

  /** @test */
  public function a_user_can_login()
  {
    $this->withoutExceptionHandling();

    $password = Str::random(10);
    $user = User::factory()->withPassword($password)->create();
    $data = ['email' => $user->email, 'password' => $password];

    $response = $this->post(route('cms.auth.log_user'), $data);

    $response->assertRedirect(route('cms.groups.index'));
  }

  /** @test */
  public function a_user_cant_login_with_wrong_password()
  {
    $this->withoutExceptionHandling();

    $password = Str::random(10);
    $user = User::factory()->withPassword(Str::random(10))->create();
    $data = ['email' => $user->email, 'password' => $password];

    $response = $this->post('/cms/auth/login', $data);

    $response->assertSessionHas('message', 'A senha está incorreta.');
    $response->assertStatus(302);
  }

  /** @test */
  public function a_user_receives_an_error_message_if_already_logged()
  {
    $this->withoutExceptionHandling();

    $password = Str::random(10);
    $user = User::factory()->withPassword($password)->create();
    $data = ['email' => $user->email, 'password' => $password];

    auth()->login($user);
    $response = $this->post('/cms/auth/login', $data);

    $response->assertSessionHas('message', 'Você já está logado.');
    $response->assertStatus(302);
  }

  /** @test */
  public function a_user_is_redirected_if_not_found()
  {
    // $this->withoutExceptionHandling();

    $password = Str::random(10);
    $data = ['email' => $this->faker->safeEmail(), 'password' => $password];

    $response = $this->post('/cms/auth/login', $data);

    $response->assertStatus(302);
  }
}

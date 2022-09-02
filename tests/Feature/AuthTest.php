<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
  // use RefreshDatabase;

  /** @test */
  public function um_usuario_pode_acessar_a_pagina_de_login()
  {
    $this->withoutExceptionHandling();

    $this->get('/cms/login')->assertStatus(200);
  }

  /** @test */
  public function um_usuario_pode_fazer_login()
  {
    $this->withoutExceptionHandling();

    $password = Str::random(10);
    $user = User::factory()->withPassword($password)->create();
    $data = ['email' => $user->email, 'password' => $password];

    $response = $this->post('/cms/auth/login', $data);

    $response->assertStatus(302);
  }
}

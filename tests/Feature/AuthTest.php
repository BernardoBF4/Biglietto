<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
  /** @test */
  public function um_usuario_pode_acessar_a_pagina_de_login()
  {
    $this->withoutExceptionHandling();

    $this->get('/cms/login')->assertStatus(200);
  }
}

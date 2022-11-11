<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  public function signIn($user = null)
  {
    $user = $user ?: User::factory()->withPassword(Str::random(10))->create();
    $this->actingAs($user);

    return $this;
  }

  public function checkIfSessionErrorMatchesString($error_index, $string)
  {
    $error_message = session('errors')->messages()[$error_index][0];
    $this->assertEquals($error_message, $string);
  }
}

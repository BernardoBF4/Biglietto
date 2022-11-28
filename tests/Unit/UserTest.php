<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @test */
  public function it_belongs_to_a_group()
  {
    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();

    $this->assertInstanceOf(Group::class, $user->group);
  }
}

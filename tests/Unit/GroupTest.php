<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
  use WithFaker;

  /** @test */
  public function a_group_has_many_modules()
  {
    $group = Group::factory()->has(Modules::factory()->count(3), 'modules')->create();

    $this->assertInstanceOf(Group::class, $group->first());
    $this->assertInstanceOf(Modules::class, $group->modules->first());
  }
}

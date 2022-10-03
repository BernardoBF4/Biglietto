<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @test */
  public function a_group_has_many_modules()
  {
    $modules = Modules::factory(10)->create();
    $group = Group::factory()->create();

    $group->modules()->attach($modules);

    foreach ($modules as $module) {
      $this->assertInstanceOf(Modules::class, $group->modules()->where('id', $module->id)->first());
    }
  }
}

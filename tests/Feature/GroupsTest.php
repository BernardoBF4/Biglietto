<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /** @test */
  public function a_logged_user_can_see_the_listing_of_groups()
  {
    $this->withoutExceptionHandling();

    $this->signIn();
    $groups = Group::factory(10)->create();

    $response = $this->get('/cms/groups');
    foreach ($groups as $group) {
      $response->assertSee($group->name);
    }
  }

  /** @test */
  public function a_logged_user_can_create_a_group()
  {
    $this->withoutExceptionHandling();

    $this->signIn();
    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()];

    $response = $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $response->assertSessionHas('message', 'Grupo criado com sucesso!');
  }
}

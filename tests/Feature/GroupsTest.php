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

    $group = Group::factory()->create();

    $response = $this->get('/cms/groups');
    $response->assertSee($group->name);
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
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $response = $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $response->assertSessionHas('message', trans('cms.groups.success_create'));
  }

  /** @test */
  public function a_logged_user_can_update_a_group()
  {
    $this->withoutExceptionHandling();
    $this->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
      'modules' => Modules::factory()->create()->pluck('id')
    ];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->id]), $group_data);

    $response->assertSessionHas('message', trans('cms.groups.success_update'));
  }

  /** @test */
  public function a_logged_user_can_exclude_a_group()
  {
    $this->withoutExceptionHandling();
    $this->signIn();

    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->delete(route('cms.groups.destroy', ['group' => $group->id]));

    $response->assertSessionHas('message', trans('cms.groups.success_delete'));
  }

  /** @test */
  public function a_group_group_cannot_be_created_without_modules()
  {
    $this->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean()
    ];

    $response = $this->post(route('cms.groups.store'), $group_data);

    $response->assertSessionHasErrors(['modules']);
  }
}

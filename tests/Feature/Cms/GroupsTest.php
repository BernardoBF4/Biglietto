<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /** @test */
  public function unauthenticated_users_are_redirected()
  {
    $this->withoutExceptionHandling();

    $response = $this->get(route('cms.groups.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function a_user_can_see_the_listing_of_groups()
  {
    $this->withoutExceptionHandling()->signIn();

    $group = Group::factory()->create();

    $response = $this->get('/cms/groups');
    $response->assertSee($group->name);
  }

  /** @test */
  public function a_group_can_be_created()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $response = $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $response->assertSessionHas('response', cms_response(trans('cms.groups.success_create')));
  }

  /** @test */
  public function creating_a_group_persists_its_data_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseCount('modules', 1);
    $this->assertDatabaseCount('group_modules', 1);
  }

  /** @test */
  public function a_group_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
      'modules' => Modules::factory(1)->create()->pluck('id')
    ];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->id]), $group_data);

    $response->assertSessionHas('response', cms_response(trans('cms.groups.success_update')));
  }

  /** @test */
  public function when_updating_a_group_if_it_snt_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
      'modules' => Modules::factory(1)->create()->pluck('id')
    ];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->id + 1]), $group_data);

    $response->assertSessionHas('response', cms_response(trans('cms.groups.error_not_found'), false, 400));
  }

  /** @test */
  public function when_a_group_is_updated_its_data_is_persisted_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $this->patch(route('cms.groups.update', ['group' => $group->id]), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseMissing('groups', ['name' => $group->name, 'status' => $group->status]);
  }

  /** @test */
  public function groups_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $groups_id = Group::factory(2)->has(Modules::factory(), 'modules')->create()->pluck('id');

    $response = $this->delete(route('cms.groups.destroy', ['group' => $groups_id]));

    $response->assertSessionHas('response', cms_response(trans('cms.groups.success_delete')));
  }

  /** @test */
  public function a_group_cannot_be_created_without_modules()
  {
    $this->signIn();

    $group_data = [
      'name' => $this->faker->word(),
      'status' => $this->faker->boolean()
    ];

    $this->post(route('cms.groups.store'), $group_data);

    $this->assertEquals(session('errors')->messages()['modules'][0], 'O grupo precisa de pelo menos um módulo.');
  }
}

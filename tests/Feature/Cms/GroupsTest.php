<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use App\Models\User;
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
  public function a_group_can_be_created()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'gro_name' => $this->faker->word(),
      'gro_status' => $this->faker->boolean(),
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
      'gro_name' => $this->faker->word(),
      'gro_status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseCount('modules', 2);
    $this->assertDatabaseCount('group_modules', 2);
  }

  /** @test */
  public function a_group_can_be_updated()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'modules' => Modules::factory(1)->create()->pluck('id'),
      'gro_name' => $this->faker->word(),
      'gro_status' => $this->faker->boolean(),
    ];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->gro_id]), $group_data);

    $response->assertSessionHas('response', cms_response(trans('cms.groups.success_update')));
  }

  /** @test */
  public function when_updating_a_group_if_it_snt_found_an_error_is_returned()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'modules' => Modules::factory(1)->create()->pluck('id'),
      'gro_name' => $this->faker->word(),
      'gro_tatus' => $this->faker->boolean(),
    ];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->gro_id + 1]), $group_data);

    $response->assertSessionHas('response', cms_response(trans('cms.groups.error_not_found'), false, 400));
  }

  /** @test */
  public function when_a_group_is_updated_its_data_is_persisted_to_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $group_data = [
      'gro_name' => $this->faker->word(),
      'gro_status' => $this->faker->boolean(),
    ];
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];
    $group = Group::factory()->has(Modules::factory(), 'modules')->create();

    $this->patch(route('cms.groups.update', ['group' => $group->gro_id]), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseMissing('groups', ['gro_name' => $group->gro_name, 'gro_status' => $group->gro_status]);
  }

  /** @test */
  public function groups_can_be_excluded()
  {
    $this->withoutExceptionHandling()->signIn();

    $groups_id = Group::factory(2)->has(Modules::factory(), 'modules')->create()->pluck('gro_id');

    $response = $this->delete(route('cms.groups.destroy', ['group' => $groups_id]));

    $response->assertSessionHas('response', cms_response(trans('cms.groups.success_delete')));
  }

  /** @test */
  public function a_group_cannot_be_created_without_modules()
  {
    $this->signIn();

    $group_data = [
      'gro_name' => $this->faker->word(),
      'gro_status' => $this->faker->boolean()
    ];

    $this->post(route('cms.groups.store'), $group_data);

    $this->assertEquals(session('errors')->messages()['modules'][0], 'O grupo precisa de pelo menos um módulo.');
  }

  /** @test */
  public function a_group_has_many_users()
  {
    $user = User::factory()->withPassword($this->faker->password(6, 12))->create();

    $group = Group::where('gro_id', $user->group->gro_id)->first();

    $this->assertInstanceOf(User::class, $group->users[0]);
  }
}

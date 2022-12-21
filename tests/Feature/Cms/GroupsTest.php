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
    $response = $this->get(route('cms.groups.index'));

    $response->assertRedirect();
  }

  /** @test */
  public function creating_a_group_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $group_data = Group::factory()->withModule()->make()->toArray();

    $response = $this->post(route('cms.groups.store'), $group_data);

    $response->assertSessionHas('response', cms_response(__('group.success.create')));
  }

  /** @test */
  public function creating_a_group_persists_its_data_to_the_database()
  {
    $this->signIn();

    $group_data = Group::factory()->make()->toArray();
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $this->post(route('cms.groups.store'), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseCount('modules', 2);
    $this->assertDatabaseCount('group_modules', 2);
  }

  /** @test */
  public function creating_a_group_without_modules_put_an_error_message_on_the_session()
  {
    $this->signIn();

    $group_data = Group::factory()->make()->toArray();

    $this->post(route('cms.groups.store'), $group_data);

    $this->checkIfSessionErrorMatchesString('modules', 'O grupo precisa de pelo menos um módulo.');
  }

  /** @test */
  public function updating_a_group_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $group = Group::factory()->hasAttached(Modules::factory(), [], 'modules')->create();
    $group_data = Group::factory()->withModule()->make()->toArray();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->gro_id]), $group_data);

    $response->assertSessionHas('response', cms_response(__('group.success.update')));
  }

  /** @test */
  public function updating_a_not_found_group_puts_an_error_message_on_the_session()
  {
    $this->signIn();

    $group = Group::factory()->hasAttached(Modules::factory(), [], 'modules')->create();
    $group_data = Group::factory()->withModule()->make()->toArray();

    $response = $this->patch(route('cms.groups.update', ['group' => $group->gro_id + 1]), $group_data);

    $response->assertSessionHas('response', cms_response(__('group.error.not_found'), false, 400));
  }

  /** @test */
  public function updating_a_group_persists_its_data_to_the_database_and_removes_the_old_data()
  {
    $this->signIn();

    $group = Group::factory()->hasAttached(Modules::factory(), [], 'modules')->create();
    $group_data = Group::factory()->make()->toArray();
    $modules = ['modules' => Modules::factory(1)->create()->pluck('id')];

    $this->patch(route('cms.groups.update', ['group' => $group->gro_id]), array_merge($group_data, $modules));

    $this->assertDatabaseHas('groups', $group_data);
    $this->assertDatabaseMissing('groups', ['gro_name' => $group->gro_name, 'gro_status' => $group->gro_status]);
  }

  /** @test */
  public function deleting_groups_puts_a_success_message_on_the_session()
  {
    $this->signIn();

    $groups_id = Group::factory(2)->hasAttached(Modules::factory(), [], 'modules')->create()->pluck('gro_id');

    $response = $this->delete(route('cms.groups.destroy', ['group' => $groups_id]));

    $response->assertSessionHas('response', cms_response(__('group.success.delete')));
  }

  /** @test */
  public function deleting_a_group_removes_its_data_from_the_database()
  {
    $this->withoutExceptionHandling()->signIn();

    $group = Group::factory()->hasAttached(Modules::factory(), [], 'modules')->create();

    $this->delete(route('cms.groups.destroy', ['group' => json_encode([$group->gro_id])]));

    $this->assertDatabaseMissing('groups', $group->toArray());
  }

  /** @test */
  public function a_group_has_many_users()
  {
    $group = Group::factory()->has(User::factory(2)->withEncryptedPassword(), 'users')->create();

    $this->assertInstanceOf(User::class, $group->users[0]);
    $this->assertInstanceOf(User::class, $group->users[1]);
  }
}

<?php

namespace Tests\Feature\Cms;

use App\Models\Group;
use App\Models\Modules;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function unauthenticated_users_are_redirected()
    {
        $response = $this->get(route('cms.users.index'));

        $response->assertRedirect();
    }

    /** @test */
    public function creating_a_user_puts_a_success_message_on_the_session()
    {
        $this->signIn();

        $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

        $response = $this->post(route('cms.users.store'), $user_data);

        $response->assertSessionHas('response', cms_response(__('user.success.create')));
    }

    /** @test */
    public function creating_a_user_persists_its_data_to_the_database()
    {
        $this->signIn();

        $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

        $this->post(route('cms.users.store'), $user_data);

        unset($user_data['usu_password_confirmation']);
        unset($user_data['usu_password']);

        $this->assertDatabaseHas('users', $user_data);
    }

    /** @test */
    public function creating_a_user_with_mismatching_passwords_puts_errors_messages_on_the_session()
    {
        $this->signIn();

        $user_data = User::factory()->withMismatchingPasswords()->make()->toArray();

        $this->post(route('cms.users.store'), $user_data);

        $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
        $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
    }

    /** @test */
    public function updating_a_user_puts_a_success_message_on_the_session()
    {
        $this->signIn();

        $user = User::factory()->withEncryptedPassword()->create();
        $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

        $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

        $response->assertSessionHas('response', cms_response(__('user.success.update')));
    }

    /** @test */
    public function updating_a_not_found_user_puts_an_error_message_on_the_session()
    {
        $this->signIn();

        $user = User::factory()->withEncryptedPassword()->create();
        $user_data = User::factory()->withPasswordAndConfirmation()->make()->toArray();

        $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id + 1]), $user_data);

        $response->assertSessionHas('response', cms_response(__('user.error.not_found'), false, 400));
    }

    /** @test */
    public function updating_a_user_without_updating_its_password_puts_a_success_message_on_the_session()
    {
        $this->signIn();

        $user = User::factory()->withEncryptedPassword()->create();
        $user_data = User::factory()->make()->toArray();

        $response = $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

        $response->assertSessionHas('response', cms_response(__('user.success.update')));
    }

    /** @test */
    public function updating_a_user_with_mismatching_passwords_puts_error_messages_on_the_session()
    {
        $this->signIn();

        $user = User::factory()->withEncryptedPassword()->create();
        $user_data = User::factory()->withMismatchingPasswords()->make()->toArray();

        $this->patch(route('cms.users.update', ['user' => $user->usu_id]), $user_data);

        $this->checkIfSessionErrorMatchesString('usu_password', 'A senha e confirmação de senha não são iguais.');
        $this->checkIfSessionErrorMatchesString('usu_password_confirmation', 'A senha e confirmação de senha não são iguais.');
    }

    /** @test */
    public function deleting_users_puts_a_success_message_on_the_session()
    {
        $this->signIn();

        $users_id = User::factory(2)->withEncryptedPassword()->create()->pluck('usu_id');

        $response = $this->delete(route('cms.users.destroy', $users_id));

        $response->assertSessionHas('response', cms_response(__('user.success.delete')));
    }

    /** @test */
    public function a_user_belongs_to_a_group()
    {
        $user = User::factory()->withEncryptedPassword()->create();

        $this->assertInstanceOf(Group::class, $user->group);
    }
}

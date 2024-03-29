<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_access_the_login_page()
    {
        $this->get('/cms/login')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_login()
    {
        $password = $this->faker->password(6, 12);
        $user = User::factory()->withEncryptedPassword($password)->create();
        $credentials = ['usu_email' => $user->usu_email, 'usu_password' => $password];

        $this->post(route('cms.auth.log_user'), $credentials);

        $this->assertEquals($user->usu_email, auth()->user()->usu_email);
    }

    /** @test */
    public function a_user_cant_login_with_wrong_password()
    {
        $password = $this->faker->password(6, 12);
        $user = User::factory()->withEncryptedPassword()->create();
        $credentials = ['usu_email' => $user->usu_email, 'usu_password' => $password];

        $response = $this->post(route('cms.auth.log_user'), $credentials);

        $response->assertSessionHas('response', cms_response(__('auth.error.wrong_password'), false, 400));
    }

    /** @test */
    public function a_user_receives_an_error_message_if_already_logged()
    {
        // Prepara variáveis
        $password = $this->faker->password(6, 12);
        $user = User::factory()->withEncryptedPassword($password)->create();
        $credentials = ['usu_email' => $user->usu_email, 'usu_password' => $password];

        // Faz as operações
        auth()->login($user);
        $response = $this->post(route('cms.auth.log_user'), $credentials);

        // Faz a asserção
        $response->assertSessionHas('response', cms_response(__('auth.error.already_logged'), false, 400));
    }

    /** @test */
    public function a_user_receives_an_error_message_if_not_found()
    {
        $data = ['usu_email' => $this->faker->safeEmail(), 'usu_password' => $this->faker->password(6, 12)];

        $this->post(route('cms.auth.log_user'), $data);

        $this->checkIfSessionErrorMatchesString('usu_email', 'Este e-mail não existe em nosso sistema.');
    }

    // /** @test */
    // public function a_user_is_logged_out_after_two_hours()
    // {
    //     $password = $this->faker->password(6, 12);
    //     $user = User::factory()->withEncryptedPassword($password)->create();
    //     $credentials = ['usu_email' => $user->usu_email, 'usu_password' => $password];

    //     $this->travel(121)->minutes();
    //     $this->post(route('cms.auth.log_user'), $credentials);
    //     $this->travelBack();

    //     $this->assertEquals(null, auth()->user());
    // }
}

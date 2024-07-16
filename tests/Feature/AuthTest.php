<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->postJson(
            uri: route('auth.register'),
            data: [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson(
            uri: route('auth.login'),
            data: [
                'username' => $user->getAttribute('username'),
                'password' => $password
            ]
        );

        $response->assertStatus(Response::HTTP_OK);

        $user = $user->refresh();

        $this->assertTrue((bool)$user->access_token);
        $this->assertTrue((bool)$user->refresh_token);
    }

    /** @test */
    public function a_user_can_refresh_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson(
            uri: route('auth.login'),
            data: [
                'username' => $user->getAttribute('username'),
                'password' => $password
            ]
        );

        $loginData = $response->getOriginalContent();
        $response->assertStatus(Response::HTTP_OK);

        $user = $user->refresh();

        $this->assertTrue((bool)$user->access_token);
        $this->assertTrue((bool)$user->refresh_token);

        $refreshRes = $this->getJson(
            uri: route('auth.refresh-token', ['refresh_token' => $loginData['data']['refresh_token']]),
        );

        $refreshRes->assertStatus(Response::HTTP_OK);

    }
}

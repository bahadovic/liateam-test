<?php

namespace App\Services;

use App\Facades\JWT;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RefreshTokenResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $params): array
    {

        $user = User::filter($params)->first();
        if (!$user) {
            return responseFormatter()->entity(error: 'login.user_and_password.not_found');
        }

        if (!Hash::check($params['password'], $user->getAttribute('password'))) {
            return responseFormatter()->entity(error: 'login.user_and_password.not_found');
        }

        $jwt = JWT::setup(user: $user);

        $user->update([
            'access_token' => $jwt['access_token'],
            'refresh_token' => $jwt['refresh_token'],
        ]);

        return responseFormatter()->success(data: LoginResource::make([
                'user' => $user,
                'access_token' => $jwt['access_token'],
                'refresh_token' => $jwt['refresh_token'],
            ]),
            message: 'login successful'
        );
    }

    public function logout(): array
    {
        auth()->user()->update([
            'access_token' => null,
            'refresh_token' => null,
        ]);
        return responseFormatter()->success(
            message: __('trans.auth.logout.successful')
        );
    }

    public function refreshToken(array $params): array
    {
        $user = User::filter($params)->first();
        if (!$user) {
            return responseFormatter()->entity(error: 'refresh_token.incorrect');
        }

        $jwt = JWT::setup(user: $user);

        $user->update([
            'access_token' => $jwt['access_token'],
            'refresh_token' => $jwt['refresh_token'],
        ]);

        return responseFormatter()->success(data: RefreshTokenResource::make($jwt), message: 'refresh_token.successful',showMessage: false);
    }

    public function register(array $params)
    {
        $user = User::filter($params)->first();
        if ($user) {
            return responseFormatter()->entity(error: 'this email is already registered');
        }

        $user = User::create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
        ]);

        return responseFormatter()->success( message: 'register.successful',showMessage: false);
    }
}

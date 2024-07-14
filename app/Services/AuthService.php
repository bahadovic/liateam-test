<?php

namespace App\Services;

use App\Facades\JWT;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RefreshTokenResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;


class AuthService
{
    public function login(array $params): array
    {

        $user = User::filter($params)->first();
        if (!$user) {
            return responseFormatter()->entity(error: 'login.user_and_password.not_found');
        }

        if (!Hash::check($params['password'], $user->getAttribute('password'))) {
            RateLimiter::hit(request()->ip(), config('auth.login_rate_limiter'));
            return responseFormatter()->entity(error: 'login.user_and_password.not_found');
        }

        $jwt = JWT::setup(user: $user);

        $user->update([
            'access_token' => $jwt['access_token'],
            'refresh_token' => $jwt['refresh_token'],
        ]);

        return responseFormatter()->success(
            data: LoginResource::make([
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token =  JWT::setup(user: $user);

        return response()->json(['token' => $token], 201);
    }
}

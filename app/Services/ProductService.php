<?php

namespace App\Services;

use App\Facades\JWT;
use App\Http\Resources\LoginResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RefreshTokenResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductService
{
    public function index(): array
    {
        $product = Product::all();
        return responseFormatter()->success(data: ProductResource::make($product), message: 'login successful');
    }

    public function store(array $params): array
    {
        Product::create($params);
        return responseFormatter()->success(message: 'product.store.successful');
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

        $token =  JWT::setup(user: $user);

        return response()->json(['token' => $token], 201);
    }
}

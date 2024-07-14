<?php

namespace App\Packages\JWT;

use App\Models\User;
use App\Packages\Constant\Constant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

final class JWTGuard implements Guard
{
    protected Request $request;
    protected UserProvider $provider;
    protected ?Authenticatable $user = NULL;
    protected ?array $claim = NULL;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function check(): bool
    {
        $jwt = request()->header('authorization');

        $jwt = trim(str_replace('Bearer', '', $jwt));

        if (!\App\Facades\JWT::isValid($jwt)) {
            return false;
        }

        $this->claim = \App\Facades\JWT::getClaim($jwt);

        return auth()->user()->getAttribute('access_token') === $jwt;
    }

    public function user()
    {
        if ($this->claim) {
            $user = User::filter([
                'id' => $this->claim['user_id'],
            ])->first();

            $this->setUser(user: $user);

            return $user;
        }
    }

    public function setUser(?Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }
}

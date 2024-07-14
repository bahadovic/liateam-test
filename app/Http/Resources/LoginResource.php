<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this['user']),
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
        ];
    }
}

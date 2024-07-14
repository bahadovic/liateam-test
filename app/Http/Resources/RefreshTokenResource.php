<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefreshTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
        ];
    }
}

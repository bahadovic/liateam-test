<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use MongoDB\Laravel\Relations\hasMany;
use MongoDB\Laravel\Eloquent\Builder;
class User extends Authenticatable
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
        'refresh_token',
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeFilter(Builder $query, array $params): Builder
    {
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (isset($params['email'])) {
            $query->where('email', $params['email']);
        }
        if (isset($params['refresh_token'])) {
            $query->where('refresh_token', $params['refresh_token']);
        }
        return $query;
    }

    public function orders() : hasMany
    {
        return $this->hasMany(Order::class);
    }
}

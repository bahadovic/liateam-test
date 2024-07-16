<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

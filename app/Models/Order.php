<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected function casts()
    {
        return [
            'products' => 'array',
        ];
    }
}

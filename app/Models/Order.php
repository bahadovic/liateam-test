<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Relations\belongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'products', 'total_price'];

    public function scopeFilter(Builder $query, array $params): Builder
    {
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }
        return $query;
    }

    public function products() :belongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    protected function casts()
    {
        return [
            'products' => 'array',
        ];
    }
}

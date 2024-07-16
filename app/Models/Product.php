<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function scopeFilter(Builder $query, array $params): Builder
    {
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }
        return $query;
    }

}

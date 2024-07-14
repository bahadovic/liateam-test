<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
class JWT extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'JWT';
    }
}

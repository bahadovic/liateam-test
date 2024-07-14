<?php

use App\Packages\Response\ResponseFormatter;

if (!function_exists('responseFormatter')) {
    function responseFormatter()
    {
        return resolve(ResponseFormatter::class);
    }
}



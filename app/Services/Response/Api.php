<?php

namespace App\Services\Response;

class Api
{
    public static function response($response, $code)
    {
        return response()->json($response, $code);
    }
}

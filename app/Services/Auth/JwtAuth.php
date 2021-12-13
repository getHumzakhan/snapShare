<?php

namespace App\Services\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtAuth
{
    public static function generate_jwt($id, $email)
    {
        date_default_timezone_set("Asia/Karachi");

        //define jwt payload
        $payload = array(
            "iss" => "snapShare",
            "iat" => time(),
            "exp" => time() + 60,
            "data" => array(
                "id" => strval($id),
                "email" => $email
            )
        );

        $jwt = JWT::encode($payload, config('jwt.secret_key'), 'HS256');
        return $jwt;
    }
}

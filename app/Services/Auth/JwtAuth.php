<?php

namespace App\Services\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Exception;

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

    public static function verify($jwt)
    {
        try{
            JWT::decode($jwt, new Key(config('jwt.secret_key'),'HS256'));
        }
        catch(Exception $e){
           return $e->getMessage();
        }
        return "hamza";
        // $authorized_user = $db->users->findOne(["jwt" => $jwt], ["projection" => ["_id" => 1]]);

        // if (isset($authorized_user)) {
        //     return iterator_to_array($authorized_user);
        // } else {
        //     return false;
        // }
    }
}

<?php

namespace App\Services\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use App\Services\Database\Instance;
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
            "exp" => time() + 3600,
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
            //if token expired, exception will be thrown.
            JWT::decode($jwt, new Key(config('jwt.secret_key'),'HS256'));
            
            $mongo = new Instance();
            $authentic_user = $mongo->db->users->findOne(["jwt" => $jwt], ["projection" => ["_id" => 1]]);
            if (isset($authentic_user)) {
                return iterator_to_array($authentic_user);
            } 
            else {
                //user is unauthentic
                return false;
            }
        }
        catch(Exception $e){
           if($e->getMessage()==="Expired token"){
               return "Expired token";
           }
        }
    }
}

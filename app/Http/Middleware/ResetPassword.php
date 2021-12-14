<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;
use Exception;

class ResetPassword
{
    public function handle(Request $request, Closure $next)
    {
        $new_password = $request->input('password');
        $id = $request->input('user_id');
        $token = $request->input('token');

        try{
            $mongo = new Instance();
            $document = $mongo->db->users->findOne(['_id'=> new \MongoDB\BSON\ObjectId($id), 'forgotPassToken'=>$token]);
            if(isset($document))
                return $next($request);
            else
                return Api::response(["Message"=>"Unauthorized Request", "Code"=>401], 401);
        }
        catch(Exception $e)
        {
            define("objectId_parse_err", 0);
            if($e->getCode() === objectId_parse_err)
                return Api::response(["Message"=>"Unauthorized Request", "Code"=>"401"], 401);
        }     
    }
}

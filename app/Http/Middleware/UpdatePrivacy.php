<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;
use Exception;

class UpdatePrivacy
{
    public function handle(Request $request, Closure $next)
    {
        $authorized_user_id = $request->_id;
        $post_id = $request->post_id;
        $privacy = $request->privacy;

        try{
            $mongo = new Instance();
            $post = $mongo->db->posts->findOne(['_id'=>new \MongoDB\BSON\ObjectId($post_id)],['projection'=>['user_id'=>1]]);
            if(isset($post)){
                if($post->user_id === strval($authorized_user_id)){
                    unset($request['_id']);
                    return $next($request);
                }else
                {
                    return Api::response(["Message" => "You can not perform this action", "Code"=>403], 403);
                }
            }
            else{
                return Api::response(["Message" => "Post Not Found", "Code"=>404], 404);
            }
        }
        catch(Exception $e){
            define("objectId_parse_err", 0);
            if($e->getCode() === objectId_parse_err)
                return Api::response(["Message"=>"Unauthorized Request", "Code"=>"401"], 401);
            else
                return $e->getMessage();
        }

    }
}

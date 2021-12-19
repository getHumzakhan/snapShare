<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;
use Exception;

class AllowPostAccess
{
    public function handle(Request $request, Closure $next)
    {
        $authentic_user_id = strval($request->input('_id'));
        $post_id = $request->input('post_id');

        try{
            $mongo = new Instance();
            $post = $mongo->db->posts->findOne(["_id" => new \MongoDB\BSON\ObjectId($post_id)], ["projection" => ["user_id"=>1]]);
            if(isset($post)){
                if($post->user_id === $authentic_user_id){
                    return $next($request);
                }
                else{
                    return Api::response(["Message" => "You have no rights to modify this resource", "Code"=>403], 403);
                }
            }
            else{
                return Api::response(["Message" => "Post not found", "Code" => "404"], 404);
            }
        }catch(Exception $e){
            define("cant_process_objectId", 0);
            if($e->getCode() === cant_process_objectId){
                return Api::response(["Message" => "Post not found", "Code" => "404"], 404);
            }
        }
    }
}

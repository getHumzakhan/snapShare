<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Response\Api;
use App\Services\Database\Instance;
use App\Services\Auth\JwtAuth;
use Exception;

class SharePost
{
    public function handle(Request $request, Closure $next)
    {
        $post_id = $request->id;
        try{
            $mongo = new Instance();
            $post = $mongo->db->posts->findOne(['_id' => new \MongoDB\BSON\ObjectId($post_id)]);
            if(isset($post)){
                if($post->privacy === "hidden")
                {
                     return Api::response(['Message' => 'Access Forbidden', 'Code' => 403], 403);
                }

                if($post->privacy === "public"){
                    $request = $request->merge(["image_path" => $post->image]);
                    return $next($request);
                }

                if($post->privacy === "private"){
                    if($request->header('jwt')){
                        $decoded_jwt = JwtAuth::verify($request->header('jwt'));
                        if($decoded_jwt === "Expired token")
                        {
                           return Api::response(['Message' => 'You need to signin first', 'Code' => 401], 401);
                        }
                        if($decoded_jwt){
                            $user_id = $decoded_jwt['_id'];
                            $user = $mongo->db->users->findOne(['_id' => $user_id], ['projection' => ['email'=>1]]);

                            if(isset($post->shared_with[$user->email]) && $post->shared_with[$user->email])
                            {
                                $request = $request->merge(["image_path" => $post->image]);
                                return $next($request);
                            }
                            else
                                return Api::response(['Message' => 'Resource is not shared with you', 'Code' => 403], 403);
                        }
                        else{
                            return Api::response(['Message' => 'Unauthentic User', 'Code' => 401], 401);
                        }
                    }
                    else{
                        return Api::response(['Message' => 'You need to signin first', 'Code' => 401], 401);
                    }
                }
            }
            else{
                return Api::response(['Message' => 'Not Found', 'Code' => 404], 404);
            }
        }
        catch(Exception $e){
            define("not_found", 0);
            if($e->getCode()===not_found)
            {
                return Api::response(['Message' => 'Not Found', 'Code' => 404], 404);
            }
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;

class DeletePost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $post_id = $request->input('post_id');   //post id that is to be deleted
        $post_id = new \MongoDB\BSON\ObjectId($post_id);
        $authentic_user_id = strval($request->input('_id'));       //user who wants to delete post

        $mongo = new Instance();
        $post = $mongo->db->posts->findOne(["_id" => $post_id], ['projection' => ['user_id' => 1,'image'=>1]]);

        if (isset($post)) {
            if ($post->user_id === $authentic_user_id) {
                $request = $request->merge(iterator_to_array($post));
                return $next($request);
            } 
            else {
                return API::response(["Message" => "Unauthorized Request"], 401);
            }
        } 
        else {
            return API::response(["Message" => "Post Not Found"], 404);
        }  
    }
}

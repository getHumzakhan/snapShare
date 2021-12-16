<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Http\Requests\CreatePost;
use App\Services\Response\Api;

class Post extends Controller
{
    public function create(CreatePost $request)
    {
        $post = $request->all();
        unset($post['_id']);
        $image = $request->file('image');
        $user_id = strval($request->input('_id'));
        try{
            $destinationPath = 'uploads/user/posts/'. $user_id;
            $file_name = bin2hex(random_bytes(10)) . $image->getClientOriginalName();
            $url = $destinationPath . "/" . $file_name;
            $user_post = $this->render_post($post, $url, $user_id, $image->getMimeType());

            $mongo = new Instance();
            $mongo->db->posts->insertOne($user_post);
            $image->move($destinationPath, $file_name);
            return Api::response(["Message" => "Picture Posted", "Code"=>"200"], 200);
        } 
        catch (Exception $e) {
            return $e->getMesage();
        }
    }

    public function render_post($post,$url,$user_id,$mime_type)
    {
        $post['image'] = base64_encode($url);
        $post['privacy'] = "hidden";
        $post['ext'] = $mime_type;
        $post['creation_time'] = date('h:i:s');
        $post['creation_date'] = date('d/m/y');
        $post['user_id'] = $user_id;
        return $post;
    }
}

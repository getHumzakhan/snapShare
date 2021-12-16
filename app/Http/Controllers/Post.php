<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\Database\Instance;
use App\Http\Requests\CreatePost;
use App\Http\Requests\DeletePost;
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

    public function delete(DeletePost $request)
    {
        $post_id = $request->input('_id');
        $img_url = $request->input('image');
        $user_id = $request->input('user_id');
        try{
            $mongo = new Instance();
            $mongo->db->posts->deleteOne(['_id' => $post_id]);
            $file_path = public_path() . '/' . base64_decode($img_url);
            FILE::delete($file_path);

            return API::response(["Message" => "Post Deleted"], 200);
        }
        catch(Exception $e){
            return $e->getMessage();
        }    
    }

    public function view(Request $request)
    {
        $user_id = strval($request->input('_id'));
        try{
            $mongo = new Instance();
            $posts = $mongo->db->posts->find(['user_id' => $user_id], ['projection'=>['image'=>1]]);
            $posts = iterator_to_array($posts);
            
            for($i=0; $i<count($posts); $i++)
            {
                $img_url = base64_decode($posts[$i]['image']);
                $user_posts[$i] = "http://127.0.0.1:8000". '/' . $img_url;
            }
            return API::response($user_posts, 200);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;

class ForgotPassToken
{
    public function handle(Request $request, Closure $next)
    {
        $user_email = $request->input('email');
        $mongo = new Instance();
        try{
            $user = $mongo->db->users->findOne(['email'=>$user_email], ['projection'=>['email'=>1,"name"=>1]]);
            if(isset($user)){
                $request = $request->merge(iterator_to_array($user));
                return $next($request);
            }
            else
                return Api::response(["Message"=>"Account Not Found", "Code"=>"404"], 404);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}

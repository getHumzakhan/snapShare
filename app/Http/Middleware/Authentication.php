<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\JwtAuth;
use App\Services\Response\Api;

class Authentication
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
        $jwt = $request->header('jwt');
        if(!isset($jwt))
        {
            return Api::response(["Message"=>"Unauthorized Request", "Code"=>"401"], 401);
        }

        $decoded_jwt = JwtAuth::verify($jwt);
        if($decoded_jwt==="Expired token")
        {
            return Api::response(["Message"=>"You need to login first", "Code"=>"401"], 401);
        }

        //if jwt in request header and db mathces, id is returned in decoded_jwt else false is returned
        if($decoded_jwt){
            $request = $request->merge($decoded_jwt);
            return $next($request);
        }
        else{
            return Api::response(["Message"=>"Unathentic user", "Code"=>"401"], 401);
        }
    }
}

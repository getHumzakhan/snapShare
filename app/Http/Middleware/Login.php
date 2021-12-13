<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Database\Instance;
use App\Services\Response\Api;
use App\Notifications\SignupNotification as Notification;

class Login
{
    public function handle(Request $user_credentials, Closure $next)
    {
        $user = $this->verify($user_credentials);

        if($user === "already logged in"){
            return Api::response(["Message" => "Already Logged in"], 200);
        }
        else if ($user === "invalid password") {
            return Api::response(["Message" => "Invalid Password"], 401);
        } 
        else if ($user === "invalid email") {
            return Api::response(["Message" => "No account associated with " . $user_credentials->input('email')], 401);
        } 
        else {
            if ($user['isVerified']) {
                $request = $user_credentials->merge($user);
                return $next($request);
            } 
            else {
                Notification::verify_account($user);
                return response()->json(["Message" => "Please Verify Your Account First Via Link Sent To Your Email"], 401);
            }
        }
    }

    //returns user's document from db if credentials are valid
    public function verify($user_credentials)
    {
        $user_email = $user_credentials->input('email');
        $user_password = $user_credentials->input('password');

        $mongo = new Instance();
        $document = $mongo->db->users->findOne(
            ['email' => $user_email],
            ["projection" => ["_id" => 1, "name" => 1, "email" => 1, "password" => 1, "age" => 1, "image" => 1, "isVerified" => 1, "verificationToken" => 1, "jwt" => 1]]
        );

        if (isset($document)) {

            if(!empty($document->jwt)){
                return "already logged in";
            }
            if ($document->password === $user_password) {
                return iterator_to_array($document);
            } 
            else
                return "invalid password";
        } 
        else
            return "invalid email";
    }
}

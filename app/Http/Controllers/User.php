<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupRequest;
use App\Notifications\SignupNotification as Notification;
use App\Services\Response\Api;
use App\Services\Database\Instance;
use Exception;

// define("dup_err_code", 11000);
class User extends Controller
{
    public function signup(SignupRequest $request_data)
    {
        $user = $request_data->all();
        $mongo = new Instance();

        //Register User
        try {
            $mongo->db->users->insertOne($user);
        } catch (Exception $e) {
            if ($e->getCode() === 11000) {
                return Api::response(["message" => "An account is already associated with " . $user['email']], 409);
            }
        }

        //send email for account verification
        $notification = $request_data->all();
        Notification::verify_account($notification);

        // generate response with message and status code
        return Api::response(["message" => "Signup Successful! Account Verification Link has been sent to " . $user['email']], 200);
    }
}

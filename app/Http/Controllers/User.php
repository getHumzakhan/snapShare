<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupRequest;
use App\Notifications\SignupNotification as Notification;
use App\Services\Response\Api;
use App\Services\Database\Instance;
use Exception;

define("dup_err_code", 11000);

class User extends Controller
{
    public function signup(SignupRequest $request_data)
    {
        $user = $request_data->all();
        $user['verificationToken'] = bin2hex(random_bytes(20));
        $user['isVerified'] = false;

        $mongo = new Instance();

        //Register User
        try {
            $mongo->db->users->insertOne($user);
        } catch (Exception $e) {
            if ($e->getCode() === dup_err_code) {
                return Api::response(["message" => "An account is already associated with " . $user['email']], 409);
            }
        }

        //send email for account verification
        Notification::verify_account($user);
        // generate response with message and status code
        return Api::response(["message" => "Signup Successful! Account Verification Link has been sent to " . $user['email']], 200);
    }

    public function verify_account(Request $request)
    {
        $token = $request->input('token');
        $mongo = new Instance();
        $result = $mongo->db->users->updateOne(['verificationToken' => $token], ['$set' => ['isVerified' => true]]);

        if (!$result->getMatchedCount())
            return Api::response(["message" => "Account not found"], 404);

        if ($result->getModifiedCount())
            return view('account_verified');
        else
            return view('already_verified');
    }

    public function verify_account_via_url($token)
    {
        $mongo = new Instance();
        $result = $mongo->db->users->updateOne(['verificationToken' => $token], ['$set' => ['isVerified' => true]]);

        if (!$result->getMatchedCount())
            return Api::response(["message" => "Account not found"], 404);

        if ($result->getModifiedCount())
            return view('account_verified');
        else
            return view('already_verified');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPass;
use App\Http\Requests\VerifyResetToken ;
use App\Notifications\SignupNotification as Notification;
use App\Services\Response\Api;
use App\Services\Database\Instance;
use App\Services\Auth\JwtAuth;
use App\Services\Email\ResetPassword;

use Exception;


define("duplicate_entry_code", 11000);

class User extends Controller
{
    public function signup(SignupRequest $request_data)
    {
        $user = $request_data->all();
        unset($user['password_confirmation']);
        $user['verificationToken'] = bin2hex(random_bytes(20));
        $user['isVerified'] = false;
        $user['jwt'] = null;

        $mongo = new Instance();

        //Register User
        try {
            $mongo->db->users->insertOne($user);
        } catch (Exception $e) {
            if ($e->getCode() === duplicate_entry_code) {
                return Api::response(["Message" => "An account is already associated with " . $user['email'], "Code"=>"409"], 409);
            }
        }

        //send email for account verification
        Notification::verify_account($user);
        // generate response with message and status code
        return Api::response(["Message" => "Signup Successful! Account Verification Link has been sent to " . $user['email'], "Code"=>"200"], 200);
    }

    public function verify_account(Request $request)
    {
        $token = $request->input('token');
        $mongo = new Instance();
        $result = $mongo->db->users->updateOne(['verificationToken' => $token], ['$set' => ['isVerified' => true]]);

        if (!$result->getMatchedCount())
            return Api::response(["Message" => "Unauthorized Request", "Code"=>"401"], 401);

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
            return Api::response(["Message" => "Unauthorized Request", "Code"=>"401"], 401);

        if ($result->getModifiedCount())
            return view('account_verified');
        else
            return view('already_verified');
    }

    public function login(Request $user_data)
    {
        $id = $user_data['_id'];
        $email = $user_data['email'];

        $jwt = JwtAuth::generate_jwt($id, $email);
        $mongo = new Instance();

        try {
            $mongo->db->users->UpdateOne(['_id' => $id], ['$set' => ['jwt' => $jwt]]);
            return API::response(["user_data" => $user_data->all(), "Code"=>"200"], 200)->withHeaders(["jwt" => $jwt]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function logout(Request $request_data)
    {
        $user_id = $request_data->_id;
        $jwt = $request_data->header('jwt');
        
        $mongo = new Instance();
        $res = $mongo->db->users->updateOne(['_id'=>$user_id],['$set'=>['jwt'=>null]]);
        
        return Api::response(["Message"=>"Successfully Logged Out", "Code"=>"200"], 200);
    }

    //verifies the account with given email and if account exists, sends an email to reset pass
    public function forgot_pass_token(ForgotPassword $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $id = $request->input('_id');
        $token = bin2hex(random_bytes(10));

        try{
            $mongo = new Instance();
            $mongo->db->users->updateOne(['_id'=> $id],['$set'=>['forgotPassToken'=>$token]]); 
            ResetPassword::email($email, $name, $token);
            return Api::response(["Message"=>"Enter the code sent to your email to reset password", "user_id"=> strval($id),"Code"=>"200"], 200);
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
       
    }
     
    public function verify_forgot_pass_token(VerifyResetToken $request)
    {
        $token = $request->input('token');
        $id = $request->input('user_id');
        try{
            $mongo = new Instance();
            $user = $mongo->db->users->findOne(['_id' => new \MongoDB\BSON\ObjectId($id), "forgotPassToken"=>$token]);
            if(isset($user))
                return Api::response(["token"=>$token , "user_id" => strval($user->_id), "Code"=>"200"], 200);
            else
                return Api::response(["Message"=>"Unauthorized Request", "Code"=>"401"], 401);
        }
        catch(Exception $e)
        {
            define("objectId_parse_err", 0);
            if($e->getCode()===objectId_parse_err)
                return Api::response(["Message"=>"Unauthorized Request", "Code"=>"401"], 401);
        }      
    }

    public function reset_pass(ResetPass $request)
    {
        $id = $request->input('user_id');
        $new_pass = $request->input('password');
        $token = $request->input('token');
        try{
            $mongo = new Instance();
            $mongo->db->users->updateOne(
                ['_id'=>new \MongoDB\BSON\ObjectId($id)], 
                ['$set'=>['password' => $new_pass], '$unset'=>['forgotPassToken' => $token]] 
            );
            return Api::response(["Message"=>"Password Changed", "Code"=>"200"], 200);
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}

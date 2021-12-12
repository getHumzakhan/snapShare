<?php

namespace App\Services\Email;

use \Mailjet\Resources;
use \Mailjet\Client;

class SignupVerification
{
    public static function get_mail_body($email, $name, $token)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "snapshareltd@gmail.com",
                        'Name' => "snapShare"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $name
                        ]
                    ],
                    'Subject' => "Account Verification",
                    'HTMLPart' => EmailTemplate::account_verify($token),
                ]
            ]
        ];
        return $body;
    }

    public static function send_email($notification)
    {
        $recipient_email = $notification['email'];
        $recipient_name = $notification['name'];
        $token = $notification['verificationToken'];

        $mail_body = SignupVerification::get_mail_body($recipient_email, $recipient_name, $token);

        $mj = new Client('706ad8399acb496215030c7afc32a8b1', 'f812d159a891360d476c63fa4d50150e', true, ['version' => 'v3.1']);
        $response = $mj->post(Resources::$Email, ['body' => $mail_body]);
        if ($response->success()) {
            return $response->getData();
        } else {
            return false;
        }
    }
}

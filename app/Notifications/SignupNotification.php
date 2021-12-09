<?php

namespace App\Notifications;

use \App\Services\Email\SignupVerification as Email;

class SignupNotification
{
    public static function verify_account($notification)
    {
        // Email::send_email($notification);
    }
}

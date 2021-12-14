<?php

namespace app\Services\Email;

class EmailTemplate
{
    public static function account_verify($token)
    {
        return
            "<html>
                        <head>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
                        </head>
                        <body style='margin: 0; background-color: whitesmoke;height:600px;'>
                            <div style='background-color: SlateBlue; height:50%;'>
                                <h2 style='text-align: center;color: #fffaf0;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:15px'>
                                    Welcome to snapShare
                                </h2>
                                <div style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;background-color: white;text-align:center;box-shadow: 1px 1px 10px #fffaf0;border-radius: 7px;width:50%;margin-left:23%;margin-top:5%; padding:20px 20px'>
                                    <h5 style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:10px'>
                                        We're excited to have you get started. First, you need to confirm your account. Just press the button below.
                                    </h5>
                                    <form action='http://127.0.0.1:8000/user/verifyAccount' method='post' style='margin:30px 10px'>
                                        <input type='hidden' name='token' value=$token>
                                        <input type='submit' value='Verify Account' style='font-size:smaller;font-family: Lato, Helvetica, Arial, sans-serif;background-color: slateblue;color: #fffaf0;align-self: center;box-shadow: 1px 1px 15px slateblue;border-radius: 10px;padding: 15px 15px;'/>
                                    </form>
                                    <p style='font-size: smaller;color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:40px;'>
                                        You can also hit the link below to verify account
                                    </p>
                                    <a style='font-size: xx-small;color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:0px' href='http://127.0.0.1:8000/user/verifyAccount/$token'>
                                        https://www.verifyMe.snapShareltd
                                    </a>
                                    <p style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;'>
                                        Cheers,<br />Team snapShare
                                    </p>
                                </div>
                            </div>
                        </body>
                    </html>";
    }

    public static function reset_password($token,$name)
    {
        return
            "<html>
                        <head>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
                        </head>
                        <body style='margin: 0; background-color: whitesmoke;height:600px;'>
                            <div style='background-color: SlateBlue; height:50%;'>
                                <h2 style='text-align: center;color: #fffaf0;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:15px'>
                                    Hey $name, snapShare got you covered
                                </h2>
                                <div style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;background-color: white;text-align:center;box-shadow: 1px 1px 10px #fffaf0;border-radius: 7px;width:50%;margin-left:23%;margin-top:5%; padding:20px 20px'>
                                    <h5 style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;padding-top:10px'>
                                        Copy & paste this code to reset your password.
                                    </h5>
                                    <form action='#'style='margin:30px 10px'>
                                        <input type='text' disabled value=$token style='width:270px;text-align:center;font-size:smaller;font-family: Lato, Helvetica, Arial, sans-serif;background-color: slateblue;color: #fffaf0;align-self: center;box-shadow: 1px 1px 15px slateblue;border-radius: 10px;padding: 15px 15px;'/>
                                    </form>
                                    <p style='color:#666666;font-family: Lato, Helvetica, Arial, sans-serif;'>
                                        Cheers,<br />Team snapShare
                                    </p>
                                </div>
                            </div>
                        </body>
                    </html>";
    }
}

<?php

namespace App\SMS;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
class SMS
{
    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    static function sendSMS($message){
        /** Get twilio credentials */
        $sid = env('TWILLIO_SID');
        $token = env('TWILLIO_USER_TOKEN');
        $twilio_number = env('TWILLIO_MOBILE_NUMBER');

        if( env('ENABLE_TWILIO')){
            //$message = substr($message, 0, 160);
            $client = new Client($sid, $token);
            $client->messages->create(
                +33652289777,
                [
                    'from' => $twilio_number,
                    'body' => $message,
                ]
            );
        }
    }
}

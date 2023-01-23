<?php

namespace App\Jobs\SendSMS;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    static function sendSMS($message){
        /** Get twilio credentials */
        $sid = env('TWILLIO_SID');
        $token = env('TWILLIO_USER_TOKEN');
        $twilio_number = env('TWILLIO_MOBILE_NUMBER');

        if( in_array(env('APP_ENV'), ['local', 'development']) ){
            $client = new Client($sid, $token);
            $client->messages->create(
                +33652289777, //todo : set customer from number
                [
                    'from' => $twilio_number,
                    'body' => $message,
                ]
            );
        }
    }
}

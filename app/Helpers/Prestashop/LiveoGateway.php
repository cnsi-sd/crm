<?php

namespace App\Helpers\Prestashop;

use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\Http;

final class LiveoGateway
{
    public static function startCall(string $email, string $phone_number): void
    {
        $logger = new Logger('api/out/prestashop/liveo.log', false, false, true, 14);

        // Build endpoint
        $endpoint = env('PRESTASHOP_URL') . 'modules/liveo/api/startCall.php';

        // Build query parameters
        $postParams = [
            'employee_email' => $email,
            'phone_number' => $phone_number,
            'liveo_api_token' => env('PRESTASHOP_LIVEO_TOKEN'),
        ];

        // Make API call
        $logger->log(sprintf('Start a call for %s to %s', $email, $phone_number));
        $response = Http::get($endpoint, $postParams);

        // Handle HTTP errors
        if($response->status() !== 200) {
            $logger->error('HTTP error : ' . $response->status());
            throw new Exception();
        }

        // Handle Prestashop errors
        if($response->body() !== 'success') {
            $logger->error('Prestashop error : ' . $response->body());
            throw new Exception('Erreur Prestashop : ' . $response->body() );
        }
    }
}

<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class PrestashopGateway
{
    protected string $endpoint;
    protected string $token;

    public function __construct()
    {
        // Get endpoint from environment
        $this->endpoint = env('PRESTASHOP_URL') . 'index.php';
        $this->token = env('PRESTASHOP_CRM_LINK_TOKEN');
    }

    protected function get(array $queryParams): Response
    {
        // Apply default parameters
        $queryParams = array_merge($queryParams, [
            'fc' => 'module',
            'module' => 'crmlink',
            'token' => $this->token,
        ]);

        // Transform array to query string
        $queryString = http_build_query($queryParams);

        // Build full URL
        $url = sprintf('%s?%s', $this->endpoint, $queryString);

        // Make HTTP call
        return Http::get($url);
    }

    public function getOrderInfo(string $marketplace_order_id, string $channel_name): array
    {
        $queryParams = [
            'controller' => 'order',
            'marketplace_order_id' => $marketplace_order_id,
            'channel_name' => $channel_name,
        ];
        return $this->get($queryParams)->json();
    }

    public function getChannels(): array
    {
        $queryParams = [
            'controller' => 'channels',
        ];
        return $this->get($queryParams)->json();
    }
}

<?php

namespace App\Helpers\Prestashop;

use Cnsi\Logger\Logger;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class CrmLinkGateway
{
    protected string $endpoint;
    protected string $token;
    protected Logger $logger;

    public function __construct()
    {
        // Get endpoint from environment
        $this->endpoint = env('PRESTASHOP_URL') . 'index.php';
        $this->token = env('PRESTASHOP_CRM_LINK_TOKEN');
        $this->logger = new Logger('api/out/prestashop/crmlink.log', false, false, true, 14);
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
        $response = Http::get($url);

        // Log errors
        if($response->serverError()) {
            $error = sprintf('Server error (5xx) calling `%s` : %s', $url, $response->body());
            $this->logger->error($error);
        }
        elseif($response->clientError()) {
            $error = sprintf('Client error (4xx) calling `%s` : %s', $url, $response->body());
            $this->logger->error($error);
        }
        elseif($response->json() === null) {
            $error = sprintf('Invalid JSON format calling `%s` : %s', $url, $response->body());
            $this->logger->error($error);
        }

        // Return response
        return $response;
    }

    public function getOrderInfo(string $marketplace_order_id, array $channel_names): ?array
    {
        $queryParams = [
            'controller' => 'order',
            'marketplace_order_id' => $marketplace_order_id,
            'channel_names' => implode(',', $channel_names),
        ];
        return $this->get($queryParams)->json();
    }

    public function getInvoiceExternalLink(): string
    {

        $queryParams = [
            'fc' => 'module',
            'module' => 'crmlink',
            'token' => $this->token,
            'controller' => 'order_invoice',
            'id_order' => '',
        ];

        // Transform array to query string
        $queryString = http_build_query($queryParams);

        // Build full URL
        $url = sprintf('%s?%s', $this->endpoint, $queryString);

        return $url;
    }

    public function getChannels(): ?array
    {
        $queryParams = [
            'controller' => 'channels',
        ];
        return $this->get($queryParams)->json();
    }

    public function getOrderInvoice(int $prestashop_order_id): ?string
    {
        $queryParams = [
            'controller' => 'order_invoice',
            'id_order' => $prestashop_order_id,
        ];
        return $this->get($queryParams)->body();
    }

    public function getIncidents(int $last_incident_id): ?array
    {
        $queryParams = [
            'controller' => 'incidents',
            'last_incident_id' => $last_incident_id,
        ];
        return $this->get($queryParams)->json();
    }
}

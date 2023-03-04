<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class BmsMagentoGateway
{
    protected string $endpoint;
    protected array $defaultQueryParams = [
        'fc' => 'module',
        'module' => 'bmsmagentogateway',
    ];

    public function __construct()
    {
        // Get endpoint from environment
        $this->endpoint = env('PRESTASHOP_URL') . 'index.php';
    }

    protected function get(array $queryParams): Response
    {
        // Apply default parameters
        $queryParams = array_merge($queryParams, $this->defaultQueryParams);

        // Transform array to query string
        $queryString = http_build_query($queryParams);

        // Build full URL
        $url = sprintf('%s?%s', $this->endpoint, $queryString);

        // Make HTTP call
        return Http::get($url);
    }

    public function getExternalOrderInfo(string $mp_order, string $mp_name)
    {
        $queryParams = [
            'controller' => 'order',
            'mp_order' => $mp_order,
            'mp_name' => $mp_name,
        ];
        return $this->get($queryParams)[0];
    }

    public function getExternalAdditionalOrderInfo(string $mp_order, string $mp_name)
    {
        $queryParams = [
            'controller' => 'order_additional_infos',
            'mp_order' => $mp_order,
            'mp_name' => $mp_name,
        ];
        return $this->get($queryParams)->json();
    }

    public function getExternalSuppliers()
    {
        $queryParams = [
            'controller' => 'supplier',
        ];
        return $this->get($queryParams)->json();
    }
}

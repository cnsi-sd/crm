<?php

namespace App\Helpers;

use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;

abstract class ParcelManagementGateway
{
    public static function getIframeUrl(Ticket $ticket): ?string
    {
        $logger = new Logger('api/out/parcel_management/parcel_management.log', max_files: 7);
        $logger->info('Request iframe URL for ticket #' . $ticket->id);

        try {
            if ($labelId = self::getLabelId($ticket->id)) {
                $logger->info('Label #' . $labelId . ' found for ticket #' . $ticket->id);
                return self::getLabelUrl($labelId);
            } else {
                $logger->info('Label not found found for ticket #' . $ticket->id);
                return self::getNewLabelUrl($ticket);
            }
        } catch (Exception $e) {
            $logger->error('Parcel Management Error', $e);
            return null;
        }
    }

    protected static function getLabelUrl($labelId): string
    {
        $queryParams = [
            'src' => 'crm',
            'jwt' => setting('pm.api_token'),
        ];

        $queryString = http_build_query($queryParams);
        return sprintf('%s/label/%s?%s', setting('pm.app_url'), $labelId, $queryString);
    }

    protected static function getNewLabelUrl(Ticket $ticket): string
    {
        $queryParams = [
            'src'           => 'crm',
            'id_shop'       => setting('pm.id_shop'),
            'id_ticket_crm' => $ticket->id,
            'sales_name'    => $ticket->user->__toString(),
            'jwt'           => setting('pm.api_token'),
        ];

        $prestashopOrders = $ticket->order->getPrestashopOrders();
        if($prestashopOrders && count($prestashopOrders)) {
            $orderReference = $prestashopOrders[0]['reference'];
            $queryParams['ref_order'] = $orderReference;
        }

        $queryString = http_build_query($queryParams);
        return sprintf('%s/label/new?%s', setting('pm.app_url'), $queryString);
    }

    protected static function getLabelId($ticketId): ?int
    {
        try {
            $apiToken = 'Bearer ' . setting('pm.api_token');
            $shopId = setting('pm.id_shop');

            $contextOptions = [
                'http' => [
                    'method' => "GET",
                    'header' => "Accept: application/json\r\n" . "Authorization: " . $apiToken . "\r\n"
                ]
            ];

            $url = sprintf('%s/label/crm/%s/%s', setting('pm.api_url'), $shopId, $ticketId);

            $response = file_get_contents($url, false, stream_context_create($contextOptions));
            $label = json_decode($response);
            return (int)$label->id ?? null;
        } catch (Exception $e) {
            return null;
        }
    }
}

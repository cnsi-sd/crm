<?php

namespace App\Helpers\Prestashop;

use App\Models\Ticket\Ticket;

final class SavProcessGateway
{
    public static function getUrl(Ticket $ticket): string
    {
        // Build endpoint
        $endpoint = setting('savprocess.url');

        // Build query parameters
        $queryParams = [
            'mp_order' => $ticket->order->channel_order_number,
            'mp_names' => implode(',', $ticket->channel->ext_names),
            'id_ticket_crm' => $ticket->id,
            'admintoken' => setting('savprocess.token'),
        ];

        // Transform parameters array to query string
        $queryString = http_build_query($queryParams);

        // Build full URL
        return sprintf('%s?%s', $endpoint, $queryString);
    }
}

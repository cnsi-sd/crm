<?php

namespace App\Console\Commands\Ticket;

use App\Enums\Channel\MirakleChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Tags\Tag;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Mirakl\MMP\Shop\Client\ShopApiClient;
use Mirakl\MMP\Shop\Domain\Order\ShopOrder;
use Mirakl\MMP\Shop\Request\Order\Get\GetOrdersRequest;

class GetMiraklRefunds extends Command
{
    protected $signature = 'ticket:getMiraklRefunds';
    protected $description = 'Get Mirakl refund messages';

    private Logger $logger;
    private Channel $channel;
    private string $channelBaseName;
    private ShopApiClient $client;

    const HTTP_CONNECT_TIMEOUT = 15;

    /**
     * @throws Exception
     */
    public function handle()
    {

        $this->logger = new Logger('ticket/getMiraklRefunds/getMiraklRefunds.log', true, true, true, 15);

        if(!setting('mirakl_refunds.active')){
            $this->logger->info('Mirakl refund setting is not activated');
            return;
        }

        try{
            $this->logger->info('---- START ----');

            foreach (MirakleChannelEnum::getList() as $marketPlace) {
                $this->logger->info('Getting refunds for '. $marketPlace .' marketplace');
                $this->getMiraklRefund($marketPlace);
            }

        } catch (Exception $e) {
            $this->logger->error('An error has occurred', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }

    /**
     * @throws Exception
     */
    protected function getMiraklRefund($marketPlace)
    {
        $date_time = new DateTime();
        $date_time->modify('-1 month');
        $this->channel = Channel::getByName($marketPlace);

        if ( str_contains($this->channel->name, '.fr') || str_contains($this->channel->name, '.com')){
            $this->channelBaseName = explode('.', $this->channel->name)[0];
        }
        elseif (str_contains($this->channel->name, 'leclerc'))
            $this->channelBaseName = 'e_leclerc';
        else
            throw new Exception('No channel match');

        if(!env(strtoupper($this->channelBaseName).'_API_URL')
            || !env(strtoupper($this->channelBaseName).'_API_KEY')
            || !env(strtoupper($this->channelBaseName).'_API_SHOP_ID'))
            return;

        $client = $this->initApiClient();

        $request = new GetOrdersRequest();
        $request->setPaginate(false)
            ->setStartUpdateDate($date_time);

        $this->logger->info('Getting orders');
        $result = $client->getOrders($request);

        foreach ($result->getItems() as $shopOrder){
            /**@var ShopOrder $shopOrder */
            foreach($shopOrder->getOrderLines()->getItems() as $orderLine) {
                if(count($orderLine->getRefunds()->getItems()) > 0) {
                    $this->logger->info('Get Mirakl refund: Refund find for ' . $shopOrder->getId());
                    $ticket = Ticket::getTicketByOrder($this->channel, $shopOrder->getId());
                    if($ticket){
                        $this->logger->info('Ticket id: ' . $ticket->id . 'associated to orderId:' . $shopOrder->getId());
                        $tagId = setting('mirakl_refunds_tag_id');
                        $refundTag = Tag::findOrFail($tagId);

                        if(!$ticket->hasTag($refundTag)){
                            $this->logger->info('No refund tag on the ticket: try to add tag to ticket');
                            $ticket->addTag($refundTag);
                        }
                    }
                }
            }
        }
    }

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env(strtoupper($this->channelBaseName).'_API_URL'),
            'API_KEY' => env(strtoupper($this->channelBaseName).'_API_KEY'),
            'API_SHOP_ID' => env(strtoupper($this->channelBaseName).'_API_SHOP_ID'),
        ];
    }

    protected function initApiClient(): ShopApiClient
    {
        $this->logger->info('Init api client');
        $credentials = $this->getCredentials();
        $this->client = new ShopApiClient(
            $credentials['API_URL'],
            $credentials['API_KEY'],
            $credentials['API_SHOP_ID'],
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}

<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RakutenImportMessages extends AbstractImportMessages
{
    private Client $client;
    private string $FROM_SHOP_TYPE;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';
    const version = '2011-09-01';

    public function __construct()
    {
        $this->signature =sprintf($this->signature,'rakuten');
        $this->FROM_SHOP_TYPE = '???'; // todo ? get shop type
        return parent::__construct();
    }

    protected function getCredentials(): array
    {
       return [
         'host'     => env('RAKUTEN_API_URL'),
         'login'    => env('RAKUTEN_LOGIN'),
         'password' => env('RAKUTEN_PASSWORD'),
         'token'    => env('RAKUTEN_TOKEN')
       ];
    }

    protected function initApiClient()
    {
        $client = new Client([
            'token' => self::getCredentials()['token'],
            //            'httpversion' => '1.1'
        ]);

        $this->client = $client;
        return $this->client;
    }
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread)
    {
        // TODO: Implement convertApiResponseToMessage() method.
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function handle()
    {
        $this->channel = Channel::getByName(ChannelEnum::RAKUTEN_COM);
        $this->logger = new Logger(
            'import_message/'
            . $this->channel->getSnakeName() . '/'
            . $this->channel->getSnakeName()
            . '.log', true, true
        );
        $this->logger->info('--- Start ---');

        // GET LAST MESSAGES
        $this->logger->info('Init api');
        $client = $this->initApiClient();

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        $response = $client->request(
            'GET', $this->getCredentials()['host']. '/sales_ws?action=getitemtodolist&login='
            . env('RAKUTEN_LOGIN')
            .'&pwd='. env('RAKUTEN_PASSWORD')
            .'&version='. self::version
            );

        $test = $response->getBody()->getContents();
        $xml = simplexml_load_string($test , "SimpleXMLElement", LIBXML_NOCDATA);

        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $this->logger->info('Get messages');
    }

}

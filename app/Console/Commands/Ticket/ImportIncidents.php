<?php

namespace App\Console\Commands\Ticket;

use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Prestashop\CrmLinkGateway;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Console\Command;
use stdClass;

class ImportIncidents extends Command
{
    protected $signature = 'ticket:incidents';
    protected $description = 'Import incidents from Prestashop and make actions on ticket';

    private Logger $logger;

    const LAST_INCIDENT_SETTING = 'last_incident_id';

    public function handle()
    {
        $this->logger = new Logger('ticket/incidents/incidents.log', true, true, true, 15);

        try {
            $this->logger->info('START');

            $this->logger->info('Get last incident ID');
            $lastIncidentId = $this->getLastIncidentId();

            $this->logger->info('Searching for incidents newer than ' . $lastIncidentId);
            $newIncidents = $this->getNewIncidents($lastIncidentId);

            $this->logger->info('Found ' . count($newIncidents) . ' new incidents');
            foreach ($newIncidents as $incident) {
                $this->processIncident($incident);
            }

            if(isset($incident)) {
                $this->logger->info('Save last incident ID (' . $incident['id'] . ')');
                $this->saveLastIncidentId($incident['id']);
            }

            $this->logger->info('DONE');
        } catch (Exception $e) {
            $this->logger->error('An error has occurred', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }

    protected function getLastIncidentId(): int
    {
        $lastIncidentId = setting(self::LAST_INCIDENT_SETTING, 0);

        // Prevent from importing old incidents in a production environment
        if ($lastIncidentId <= 0) {
            throw new Exception('Please define an initial incident ID (setting `' .self::LAST_INCIDENT_SETTING . '`)');
        }

        return (int)$lastIncidentId;
    }

    protected function getNewIncidents(int $lastIncidentId): array
    {
        $gateway = new CrmLinkGateway();
        $incidents = $gateway->getIncidents($lastIncidentId);

        if (!is_array($incidents))
            throw new Exception('Prestashop did not return an array. Get: ' . json_encode($incidents));

        return $incidents;
    }

    protected function processIncident(array $incident)
    {
        $id = $incident['id'];
        $channel_order_number = $incident['id_order_marketplace'];
        $ext_channel_name = $incident['marketplace'];

        $this->logger->info(sprintf('Process incident #%d (%s : %s)', $id, $ext_channel_name, $channel_order_number));

        // Get channel
        $channel = Channel::getByExtName($ext_channel_name);
        if(!$channel) {
            $this->logger->error('Incident ' . $id . ' unprocessable, channel not found for `' . $ext_channel_name . '`');
            return;
        }

        // Get ticket
        $order = Order::getOrder($channel_order_number, $channel);
        $ticket = Ticket::getTicket($order, $channel);

        // Grab incident tag
        // TODO : this is dangerous
        $tag = Tag::query()->where('name', 'Incident')->firstOrFail();

        // Update ticket
        if (!$ticket->hasTag($tag))
            $ticket->addTag($tag);
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->deadline = Ticket::getAutoDeadline();
        $ticket->save();

        if ($ticket->threads->count() === 0) {
            // TODO : this make no senses
            Thread::getOrCreateThread($ticket, 'default', 'Discussion');
        }
    }

    protected function saveLastIncidentId($lastIncidentId): void
    {
        setting([self::LAST_INCIDENT_SETTING => $lastIncidentId]);
        setting()->save();
    }
}

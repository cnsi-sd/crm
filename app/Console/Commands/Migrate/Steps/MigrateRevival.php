<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MagentoStores;
use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Revival\Revival;
use Closure;

class MigrateRevival extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $this->migrateRevivals($dto);
        $this->migrateChannelsRevivals($dto);
        return $next($dto);
    }

    private function migrateRevivals(MigrateDTO $dto)
    {
        $defaultAnswers = DefaultAnswer::all()->pluck('id', 'name');

        $data = $dto->connection->select('
            SELECT cr_name as name,
                   cr_revival_frequency as frequency,
                   cr_revival_type as send_type,
                   dr1.cdr_name as default_answer_name,
                   cr_max_revival as max_revival,
                   dr2.cdr_name as end_default_answer_name,
                   cr_revival_end_status as end_state
            FROM crm_revival
            INNER JOIN crm_default_reply dr1 ON dr1.cdr_id = cr_defaultreply_id
            INNER JOIN crm_default_reply dr2 ON dr2.cdr_id = cr_revival_end_defaultreply_id
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);
        foreach ($data as &$revival) {
            // Convert Magento SendType
            $revival['send_type'] = $revival['send_type'] === 'sms' ? 'sms' : 'channel';

            // Get default_answer_id from name
            $revival['default_answer_id'] = $defaultAnswers->offsetGet($revival['default_answer_name']);
            unset($revival['default_answer_name']);

            // Get end_default_answer_id from name
            $revival['end_default_answer_id'] = $defaultAnswers->offsetGet($revival['end_default_answer_name']);
            unset($revival['end_default_answer_name']);
        }

        Revival::truncate();
        Revival::insert($data);

        $dto->logger->info(count($data) . ' Revivals inserted');
    }

    private function migrateChannelsRevivals(MigrateDTO $dto)
    {
        // Load tags and mapping
        $revivals = Revival::all()->keyBy('name');
        $mapping = MagentoStores::getStoreMapping();

        // prepare statistics vars
        $not_filtered = 0;
        $filtered = 0;

        // Load magento associations
        $magentoRevivalAssociations = $dto->connection->select('
            SELECT cr_name as name, GROUP_CONCAT(code SEPARATOR ",") as store_codes
            FROM crm_store_revival
            INNER JOIN core_store ON store_id = csr_store_id
            INNER JOIN crm_revival ON cr_id = csr_revival_id
            GROUP BY cr_name
        ');

        $magentoRevivalAssociations = $this->toArray($magentoRevivalAssociations);
        foreach ($magentoRevivalAssociations as $magentoRevivalAssociation) {
            if(!$revivals->offsetExists($magentoRevivalAssociation['name']))
                continue;

            /** @var Revival $revival */
            $revival = $revivals->offsetGet($magentoRevivalAssociation['name']);
            $store_codes = explode(',', $magentoRevivalAssociation['store_codes']);

            // Convert magento store code to crm_channel_id
            $authorizedOnAllChannels = true;
            $authorizedChannels = [];
            foreach ($mapping as $magento_store_code => $crm_channel) {
                if (in_array($magento_store_code, $store_codes)) {
                    $authorizedChannels[] = $crm_channel->id;
                } else {
                    $authorizedOnAllChannels = false;
                }
            }

            if ($authorizedOnAllChannels) {
                $not_filtered++;
                $revival->channels()->sync([]);
            } else {
                $revival->channels()->sync($authorizedChannels);
                $filtered++;
            }
        }

        $dto->logger->info('-- ' . $not_filtered . ' Not filtered revivals');
        $dto->logger->info('-- ' . $filtered . ' Filtered revivals');
    }
}

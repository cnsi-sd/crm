<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MagentoStores;
use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\DefaultAnswer;
use Closure;

class MigrateDefaultAnswer extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $this->migrateDefaultAnswers($dto);
        $this->migrateChannelsDefaultAnswers($dto);
        return $next($dto);
    }

    private function migrateDefaultAnswers(MigrateDTO $dto)
    {
        $data = $dto->connection->select('
            SELECT cdr_name as name, cdr_content as content
            FROM crm_default_reply
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);

        DefaultAnswer::truncate();
        DefaultAnswer::insert($data);

        $dto->logger->info(count($data) . ' DefaultAnswers inserted');
    }

    private function migrateChannelsDefaultAnswers(MigrateDTO $dto)
    {
        // Load tags and mapping
        $defaultAnswers = DefaultAnswer::all()->keyBy('name');
        $mapping = MagentoStores::getStoreMapping();

        // prepare statistics vars
        $not_filtered = 0;
        $filtered = 0;

        // Load magento associations
        $magentoDefaultAnswerAssociations = $dto->connection->select('
            SELECT cdr_name as name, GROUP_CONCAT(code SEPARATOR ",") as store_codes
            FROM crm_store_default_reply
            INNER JOIN core_store ON store_id = csdr_store_id
            INNER JOIN crm_default_reply ON cdr_id = csdr_default_reply_id
            GROUP BY cdr_name
        ');

        $magentoDefaultAnswerAssociations = $this->toArray($magentoDefaultAnswerAssociations);
        foreach ($magentoDefaultAnswerAssociations as $magentoDefaultAnswerAssociation) {
            if(!$defaultAnswers->offsetExists($magentoDefaultAnswerAssociation['name']))
                continue;

            /** @var DefaultAnswer $defaultAnswer */
            $defaultAnswer = $defaultAnswers->offsetGet($magentoDefaultAnswerAssociation['name']);
            $store_codes = explode(',', $magentoDefaultAnswerAssociation['store_codes']);

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
                $defaultAnswer->channels()->sync([]);
            } else {
                $defaultAnswer->channels()->sync($authorizedChannels);
                $filtered++;
            }
        }

        $dto->logger->info('-- ' . $not_filtered . ' Not filtered defaultAnswers');
        $dto->logger->info('-- ' . $filtered . ' Filtered defaultAnswers');
    }
}

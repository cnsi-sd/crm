<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MagentoStores;
use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Tags\Tag;
use Closure;

class MigrateTag extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $this->migrateTags($dto);
        $this->migrateChannelsTags($dto);
        return $next($dto);
    }

    private function migrateTags(MigrateDTO $dto)
    {
        $data = $dto->connection->select('
            SELECT ctg_name as name,
                   ctg_bg_color as background_color,
                   ctg_text_color as text_color
            FROM crm_tag
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);
        foreach ($data as &$tag) {
            $tag['background_color'] = '#' . $tag['background_color'];
            $tag['text_color'] = '#' . $tag['text_color'];
        }

        Tag::truncate();
        Tag::insert($data);

        $dto->logger->info(count($data) . ' Tags inserted');
    }

    private function migrateChannelsTags(MigrateDTO $dto)
    {
        // Load tags and mapping
        $tags = Tag::all()->keyBy('name');
        $mapping = MagentoStores::getStoreMapping();

        // prepare statistics vars
        $not_filtered = 0;
        $filtered = 0;

        // Load magento associations
        $magentoTagAssociations = $dto->connection->select('
            SELECT ctg_name as name, GROUP_CONCAT(code SEPARATOR ",") as store_codes
            FROM crm_store_tag
            INNER JOIN core_store ON store_id = cst_store_id
            INNER JOIN crm_tag ON ctg_id = cst_tag_id
            GROUP BY ctg_name
        ');

        $magentoTagAssociations = $this->toArray($magentoTagAssociations);
        foreach ($magentoTagAssociations as $magentoTagAssociation) {
            if(!$tags->offsetExists($magentoTagAssociation['name']))
                continue;

            /** @var Tag $tag */
            $tag = $tags->offsetGet($magentoTagAssociation['name']);
            $store_codes = explode(',', $magentoTagAssociation['store_codes']);

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
                $tag->channels()->sync([]);
            } else {
                $tag->channels()->sync($authorizedChannels);
                $filtered++;
            }
        }

        $dto->logger->info('-- ' . $not_filtered . ' Not filtered tags');
        $dto->logger->info('-- ' . $filtered . ' Filtered tags');
    }
}

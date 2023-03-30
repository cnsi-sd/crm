<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Tags\Tag;
use Closure;

class MigrateTag extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $data = $dto->connection->select('
            SELECT ctg_name as name,
                   ctg_bg_color as background_color,
                   ctg_text_color as text_color
            FROM crm_tag
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);

        Tag::truncate();
        Tag::insert($data);

        $dto->logger->info(count($data) . ' Tags inserted');

        return $next($dto);
    }
}

<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\DefaultAnswer;
use Closure;

class MigrateDefaultAnswer extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $data = $dto->connection->select('
            SELECT cdr_name as name, cdr_content as content
            FROM crm_default_reply
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);

        DefaultAnswer::truncate();
        DefaultAnswer::insert($data);

        $dto->logger->info(count($data) . ' DefaultAnswers inserted');

        return $next($dto);
    }
}

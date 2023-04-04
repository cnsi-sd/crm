<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use Closure;

abstract class AbstractMigrateStep
{
    abstract public function handle(MigrateDTO $dto, Closure $next);

    protected function toArray($data): array
    {
        return json_decode(json_encode($data), 1);
    }

    protected function toArrayWithCreatedAndUpdated($data): array
    {
        $array = $this->toArray($data);
        foreach($array as &$line) {
            $line['created_at'] = now()->format('Y-m-d H:i:s');
            $line['updated_at'] = now()->format('Y-m-d H:i:s');
        }
        return $array;
    }
}

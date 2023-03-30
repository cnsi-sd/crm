<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Revival\Revival;
use Closure;

class MigrateRevival extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
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

        return $next($dto);
    }
}

<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Console\Commands\Migrate\Tools\TicketFinder;
use App\Models\Tags\Tag;
use Closure;
use Illuminate\Support\Facades\DB;

class MigrateTicketTags extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        DB::table('tag_tagLists')->truncate();
        DB::table('tagLists')->truncate();

        $tags = Tag::all()->keyBy('name');
        $magentoTags = $dto->connection->select('
            SELECT
                ctt_ct_id as ct_id,
                ctg_name as tag_name
            FROM crm_ticket_tag
            INNER JOIN crm_tag ON ctg_id = ctt_ctg_id
        ');
        $inserted = 0;

        foreach ($magentoTags as $magentoTicketTag) {
            // Ignore unrecognised tags
            if (!$tags->offsetExists($magentoTicketTag->tag_name))
                continue;

            // Ignore tags on not imported tickets
            if (!isset(TicketFinder::$ticketsByMagentoId[$magentoTicketTag->ct_id]))
                continue;

            $ticket = TicketFinder::$ticketsByMagentoId[$magentoTicketTag->ct_id];
            $tag = $tags->offsetGet($magentoTicketTag->tag_name);
            if (!$ticket->hasTag($tag)) {
                $ticket->addTag($tag);
                $inserted++;
            }
        }

        $dto->logger->info($inserted . ' tags associated to tickets');
        return $next($dto);
    }
}

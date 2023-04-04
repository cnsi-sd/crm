<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Console\Commands\Migrate\Tools\TicketFinder;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Helpers\TinyMCE;
use App\Models\Ticket\Comment;
use Closure;

class MigrateTicketComments extends AbstractMigrateStep
{

    public function handle(MigrateDTO $dto, Closure $next)
    {
        Comment::truncate();

        $magentoComments = $dto->connection->select('
            SELECT ct_id, ct_private_comments
            FROM crm_ticket
            WHERE ct_private_comments != ""
        ');

        $comments = [];
        foreach ($magentoComments as $magentoComment) {
            // Ignore comments on not imported tickets
            if (!isset(TicketFinder::$ticketsByMagentoId[$magentoComment->ct_id])) {
                continue;
            }

            $comments[] = [
                'ticket_id'  => TicketFinder::$ticketsByMagentoId[$magentoComment->ct_id]->id,
                'content'    => TinyMCE::toText($magentoComment->ct_private_comments),
                'displayed'  => true,
                'type'       => TicketCommentTypeEnum::OTHERS,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($comments, 1000);
        foreach ($chunks as $chunk)
            Comment::insert($chunk);

        $dto->logger->info(count($comments) . ' comments inserted');
        return $next($dto);
    }
}

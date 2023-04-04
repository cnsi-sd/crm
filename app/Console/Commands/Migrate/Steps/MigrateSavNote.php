<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\SavNote;
use Closure;

class MigrateSavNote extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $data = $dto->connection->select('
            SELECT fabricant as manufacturer,
                   delai_pms as pms_delay,
                   garantie_constructeur as manufacturer_warranty,
                   gc_plus as gc_plus,
                   delai_realisation_contrat_gc_plus as gc_plus_delay,
                   hotline as hotline,
                   email_marque as brand_email,
                   information_marque as brand_information,
                   information_regionales as regional_information
            FROM sav_notes
        ');

        $data = $this->toArrayWithCreatedAndUpdated($data);
        foreach ($data as &$sav_note) {
            $sav_note['gc_plus'] = $sav_note['gc_plus'] === 'OUI';
        }

        SavNote::truncate();
        SavNote::insert($data);

        $dto->logger->info(count($data) . ' SavNotes inserted');

        return $next($dto);
    }
}

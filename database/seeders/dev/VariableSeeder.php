<?php

namespace Database\Seeders\dev;

use App\Enums\Ticket\MessageVariable;
use Illuminate\Database\Seeder;

class VariableSeeder extends Seeder
{
    public function run()
    {
        MessageVariable::SIGNATURE_BOT->saveValue('Olympe');
        MessageVariable::NOM_BOUTIQUE->saveValue('Icoza');
        MessageVariable::TELEPHONE_BOUTIQUE->saveValue('0 971 00 60 44');
    }
}

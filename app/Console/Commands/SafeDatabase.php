<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SafeDatabase extends Command
{
    protected $signature = 'db:safe';
    protected $description = 'Remove sensitive data from the database';

    /**
     * @throws Exception
     */
    public function handle()
    {
        if (!in_array(env('APP_ENV'), ['local', 'development']))
            throw new Exception('this command ca only use in a local env');

        User::query()->update([
            'password' => Hash::make('password'),
        ]);

        setting()->forget('bot');
        setting()->save();

        $this->info('-- DONE');
    }
}

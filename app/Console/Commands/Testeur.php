<?php

namespace App\Console\Commands;

class Testeur extends \Illuminate\Console\Command
{
    protected $signature = 'testeur';
    protected $description = 'Send revival on eligible threads';

    public function handle()
    {
        $lock = new \Cnsi\Lock\Lock('test',60,120,'kental0910@gmail.com');
        $lock->lock();
        sleep(160);
        $lock->remove();
    }
}

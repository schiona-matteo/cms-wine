<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SessionGcCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:gc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $session = $this->getLaravel()->make('session');
        $lifetime = Arr::get($session->getSessionConfig(), 'lifetime') * 60;
        $session->getHandler()->gc($lifetime);

        return 0;
    }
}

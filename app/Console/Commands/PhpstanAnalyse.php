<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PhpstanAnalyse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpstan:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run phpstan / larastan analyse';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo shell_exec('./vendor/bin/phpstan analyse --memory-limit=4G');

        return 0;
    }
}

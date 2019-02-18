<?php

namespace App\Console\Commands\Cron;

use Illuminate\Console\Command;

class Simulate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Only for develompent. Simulate a fake cron.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // in production it should just execute
        set_time_limit(0);
        while (true) {
            shell_exec("php artisan cron:schedule 1>> /dev/null 2>&1");
            sleep(60);
        }
    }
}

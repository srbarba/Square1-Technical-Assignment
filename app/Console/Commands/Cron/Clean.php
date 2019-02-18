<?php

namespace App\Console\Commands\Cron;

use Illuminate\Console\Command;
use App\Models\Cron\Schedule as ScheduleModel;
use Carbon\Carbon;

class Clean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This process clean not finished or timeout scheduled commands.';

    /**
     * The schedule model instance.
     *
     * @var App\Models\Cron\Schedule
     */
    protected $scheduleModel;

    /**
     * Create a new command instance.
     *
     * @param  App\Models\Cron\Schedule
     * @return void
     */
    public function __construct(ScheduleModel $scheduleModel)
    {
        $this->scheduleModel = $scheduleModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scheduleExpiredCommands = $this->scheduleModel->where([
            ["expired_at", "<", Carbon::now()->toDateTimeString()],
            ["status", "=", "processing"]
        ]);
        if( $scheduleExpiredCommands->count() > 0 ){
            foreach ($scheduleExpiredCommands->cursor() as $key => $scheduleExpiredCommand) {
                $scheduleExpiredCommand->status = "error";
                $scheduleExpiredCommand->message = "EXPIRED:: ".$scheduleExpiredCommand->message;
            }
        }
    }
}

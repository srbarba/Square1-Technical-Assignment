<?php

namespace App\Console\Commands\Cron;

use Illuminate\Console\Command;
use Jobby\Jobby;
use App\Models\Cron\Schedule as ScheduleModel;
use Carbon\Carbon;

class Schedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule all proccess on cron_schedule table.';

    /**
     * The schedule class instance.
     *
     * @var Jobby\Jobby
     */
    protected $schedule;

    /**
     * The schedule model instance.
     *
     * @var App\Models\Cron\Schedule
     */
    protected $scheduleModel;

    /**
     * Create a new command instance.
     *
     * @param  Jobby\Jobby
     * @param  App\Models\Cron\Schedule
     * @return void
     */
    public function __construct(
        Jobby $schedule,
        ScheduleModel $scheduleModel
    ){
        $this->schedule = $schedule;
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
        if( env('APP_ENV') == "development" && $this->scheduleModel->all()->count() == 0 ){
            $this->schedule->add('FirstImport', [
                'command'  => 'php artisan import:start',
                'schedule' => Carbon::now()->addSeconds(5)->toDateTimeString(),
                'output'   => base_path().'/storage/logs/schedule.log',
            ]);
        }

        // Getting current scheduled commands
        $scheduleCommands = $this->scheduleModel->where([
            ["scheduled_at", ">=", Carbon::now()->toDateTimeString()],
            ["status", "=", "pending"],
        ]);

        if( $scheduleCommands->count() > 0 ){ // If we have some schedule pending
            // For each command we schedule the execution
            foreach ($scheduleCommands->cursor() as $key => $scheduleCommand) {
                $this->schedule->add('ScheduleCommand'.$key, [
                    'command'  => $scheduleCommand->command,
                    'schedule' => $scheduleCommand->scheduled_at,
                    'output'   => base_path().'/storage/logs/schedule.log',
                ]);
            }
        }

        // Clear all the expired commands
        $this->schedule->add('CleanSchedule', [
            'command'  => 'php artisan cron:clean',
            'schedule' => '*/15 * * * *',
            'output'   => base_path().'/storage/logs/schedule.log',
        ]);

        // TODO: this command will be added from config and not hard coded.
        $this->schedule->add('StartImport', [
            'command'  => 'php artisan import:start',
            'schedule' => '0 3 * * *',
            'output'   => base_path().'/storage/logs/schedule.log',
        ]);

        $this->schedule->run();
    }
}

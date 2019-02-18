<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Carbon\Carbon;

class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the import from origin website.';

    /**
     * TODO: This will be implemented from config and not hard coded.
     *
     * @var array
     */
    protected $categories = [
        "small-appliances",
        "dishwashers"
    ];

    /**
     * Create a new command instance.
     *
     * @param  App\Models\Cron\Schedule
     * @param  App\Models\Catalog\Category\Category
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->categories as $slug) {
            $this->scheduleModel->create([
                "command" => "php artisan import:category ".$slug,
                "status" => ScheduleModel::STATUS_PENDING,
                "message" => "Scheduled category ".$slug,
                "scheduled_at" => Carbon::now()->addMinute()->toDateTimeString(),
                "expired_at" => Carbon::now()->addHour()->toDateTimeString(),
            ]);
        }
    }

}

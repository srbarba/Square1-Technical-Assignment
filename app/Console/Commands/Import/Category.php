<?php

namespace App\Console\Commands\Import;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Cron\Schedule as ScheduleModel;
use App\Models\Catalog\Category\Category as CategoryModel;
use Carbon\Carbon;

class Category extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:category {cat : Category to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import category from origin website.';

    /**
     * The schedule model instance.
     *
     * @var App\Models\Cron\Schedule
     */
    protected $scheduleModel;

    /**
     * The category model instance.
     *
     * @var App\Models\Catalog\Category\Category
     */
    protected $categoryModel;

    /**
     * Create a new command instance.
     *
     * @param  App\Models\Cron\Schedule
     * @param  App\Models\Catalog\Category\Category
     * @return void
     */
    public function __construct(
        ScheduleModel $scheduleModel,
        CategoryModel $categoryModel
        )
    {
        $this->scheduleModel = $scheduleModel;
        $this->categoryModel = $categoryModel;
        parent::__construct();
    }

    /**
     * Execute the console command:
     * 1. Get category from API
     * 2. Update or Create the category
     * 3. Add a new schedule for each page in category pagination
     * 4. Update the schedule entry as completed
     *
     * @return void
     */
    public function handle()
    {
        $slug = $this->argument('cat');

        // Get the current schedule executed.
        $currentSchedule = $this->scheduleModel->where([
            "status" => ScheduleModel::STATUS_PENDING,
            "command" => "php artisan import:category ".$slug,
        ])->first();

        if(!$currentSchedule) return;

        // Update the status, message and add executed time.
        $currentSchedule->update([
            "status" => ScheduleModel::STATUS_PROCESSING,
            "message" => "Processing schedule category ".$slug,
            "executed_at" => Carbon::now()->toDateTimeString()
        ]);

        // Get info from API (by the moment we get it from internal route).
        $apiResponse = json_decode(
            app()['Illuminate\Contracts\Http\Kernel']->handle(
                Request::create("/api/simulate/catalog/category/".$slug, 'GET')
            )->content(), true
        );

        // If error code, update and finish the schedule.
        if( $apiResponse["code"] != 200 ){
            $currentSchedule->update([
                "status" => ScheduleModel::STATUS_ERROR,
                "message" => "There was an api error: ".$apiResponse["message"],
                "finished_at" => Carbon::now()->toDateTimeString()
            ]);
            return;
        }

        // Check if category exist and update/create.
        $category = $this->categoryModel->updateOrCreate(
            ["slug" => $slug],
            $apiResponse["data"]
        );

        // For each page, add a new schedule to import products.
        $totalPages = ceil($apiResponse["data"]["total_products"] / CategoryModel::PAGINATE)+1;
        for ($i=1; $i < $totalPages; $i++) {
            $time = ceil($i/1);
            $this->scheduleModel->create([
                "command" => "php artisan import:products ".$slug." ".$i,
                "status" => ScheduleModel::STATUS_PENDING,
                "message" => "Scheduled category ".$slug,
                "scheduled_at" => Carbon::now()->addMinutes($time)->toDateTimeString(),
                "expired_at" => Carbon::now()->addHours($time)->toDateTimeString(),
            ]);
        }

        // Update the status, message and add finished time.
        $currentSchedule->update([
            "status" => ScheduleModel::STATUS_COMPLETED,
            "message" => "Completed successfully",
            "finished_at" => Carbon::now()->toDateTimeString()
        ]);

    }
}

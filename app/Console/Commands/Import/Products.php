<?php

namespace App\Console\Commands\Import;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Cron\Schedule as ScheduleModel;
use App\Models\Catalog\Category\Category as CategoryModel;
use App\Models\Catalog\Product\Product as ProductModel;
use App\Models\Catalog\Product\ProductImage as ProductImageModel;
use App\Models\Catalog\Product\ProductVideo as ProductVideoModel;
use Carbon\Carbon;

class Products extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products {cat : Category to import} {page : Category Page to inspect}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from origin website.';

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
     * The product model instance.
     *
     * @var App\Models\Catalog\Product\Product
     */
    protected $productModel;

    /**
     * The product image model instance.
     *
     * @var App\Models\Catalog\Product\ProductImage
     */
    protected $productImageModel;

    /**
     * The product video model instance.
     *
     * @var App\Models\Catalog\Product\ProductVideo
     */
    protected $productVideoModel;

    /**
     * Create a new command instance.
     *
     * @param  App\Models\Cron\Schedule
     * @param  App\Models\Catalog\Category\Category
     * @param  App\Models\Catalog\Product\Product
     * @param  App\Models\Catalog\Product\ProductImage
     * @param  App\Models\Catalog\Product\ProductVideo
     * @return void
     */
    public function __construct(
        ScheduleModel $scheduleModel,
        CategoryModel $categoryModel,
        ProductModel $productModel,
        ProductImageModel $productImageModel,
        ProductVideoModel $productVideoModel
        )
    {
        $this->scheduleModel = $scheduleModel;
        $this->categoryModel = $categoryModel;
        $this->productModel = $productModel;
        $this->productImageModel = $productImageModel;
        $this->productVideoModel = $productVideoModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 1. Get category from API
     * 2. Update or Create the category
     * 3. Add a new schedule for each page in category pagination
     * 4. Update the schedule entry as completed
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->argument('cat');
        $i = $this->argument('page');

        // Get the current schedule executed.
        $currentSchedule = $this->scheduleModel->where([
            "status" => ScheduleModel::STATUS_PENDING,
            "command" => "php artisan import:products ".$slug." ".$i,
        ])->first();

        if(!$currentSchedule) return;

        // Update the status, message and add executed time.
        $currentSchedule->update([
            "status" => ScheduleModel::STATUS_PROCESSING,
            "message" => "Processing schedule category ".$slug." page ".$i,
            "executed_at" => Carbon::now()->toDateTimeString()
        ]);

        // Get info from API (by the moment we get it from internal route).
        $apiResponse = json_decode(
            app()['Illuminate\Contracts\Http\Kernel']->handle(
                Request::create("/api/simulate/catalog/category/".$slug."/".$i, 'GET')
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

        $products = $apiResponse["data"]["products"];
        foreach ($products as $product) {
            $apiResponse = json_decode(
                app()['Illuminate\Contracts\Http\Kernel']->handle(
                    Request::create("/api/simulate/catalog/product/".$product["slug"]."/".$product["id"], 'GET')
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
            // Add/update product
            $prodModel = $this->productModel->updateOrCreate(
                ["imported_id" => $apiResponse["data"]["product"]["id"]],
                $apiResponse["data"]["product"]
            );

            // Add categories and asociate to product
            if( count($apiResponse["data"]["categories"]) > 0 ) {
                // AÑADIMOS CATEGORÍAS
                $currentProductCategories = $prodModel->categories()->get();
                $newCategoriesIds = [];
                $categoryParent = false;

                foreach ($apiResponse["data"]["categories"] as $category) {
                    $catModel = $this->categoryModel->firstOrCreate(
                        ["slug" => $category["slug"]],
                        $category
                    );

                    if($categoryParent) $catModel->parent()->associate($categoryParent)->save();

                    $prodModel->addCategory($catModel);

                    $categoryParent = $catModel;
                    $newCategoriesIds[] = $catModel->category_id;
                }
                foreach ($currentProductCategories as $category) {
                    $categoryId = $category->category_id;
                    if( !in_array($categoryId, $newCategoriesIds) ){
                        $prodModel->categories()->detach($categoryId);
                    }
                }
            }

            // Add images and asociate to product
            if( count($apiResponse["data"]["images"]) > 0 ) {
                $currentProductImages = $prodModel->images();
                $newImagesIds = [];

                foreach ($apiResponse["data"]["images"] as $image) {
                    $imageEntry = $this->productImageModel->updateOrCreate(
                        ["thumbnail" => $image["thumbnail"]],
                        $image
                    );
                    $imageEntry->product()->associate($prodModel)->save();
                    $newImagesIds[] = $imageEntry->image_id;
                }
                foreach ($currentProductImages as $image) {
                    if( !in_array($image->image_id, $newImagesIds) ){
                        $image->delete();
                    }
                }
            }

            // Add videos and asociate to product
            if( count($apiResponse["data"]["videos"]) > 0 ) {
                $currentProductVideos = $prodModel->videos();
                $newVideosIds = [];
                foreach ($apiResponse["data"]["videos"] as $video) {
                    $videoEntry = $this->productVideoModel->updateOrCreate(
                        ["url" => $video["url"]],
                        $video
                    );
                    $videoEntry->product()->associate($prodModel)->save();
                    $newVideosIds[] = $videoEntry->video_id;
                }
                foreach ($currentProductVideos as $video) {
                    if( !in_array($video->video_id, $newVideosIds) ){
                        $video->delete();
                    }
                }
            }
        }

        // Update the status, message and add finished time.
        $currentSchedule->update([
            "status" => ScheduleModel::STATUS_COMPLETED,
            "message" => "Completed successfully",
            "finished_at" => Carbon::now()->toDateTimeString()
        ]);

    }
}

<?php

namespace App\Console\Commands\Import;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Catalog\Category\Category as CategoryModel;
use App\Models\Catalog\Product\Product as ProductModel;
use App\Models\Catalog\Product\ProductImage as ProductImageModel;
use App\Models\Catalog\Product\ProductVideo as ProductVideoModel;
use Carbon\Carbon;

class All extends Command
{
    protected $categories = [
        "small-appliances",
        "dishwashers"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute all import command at once.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CategoryModel $categoryModel,
        ProductModel $productModel,
        ProductImageModel $productImageModel,
        ProductVideoModel $productVideoModel
        )
    {
        $this->categoryModel = $categoryModel;
        $this->productModel = $productModel;
        $this->productImageModel = $productImageModel;
        $this->productVideoModel = $productVideoModel;
        parent::__construct();
    }

    /**
     * Execute the console command
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        foreach ($this->categories as $slug) {
            // Get info from API (by the moment we get it from internal route).
            $apiResponse = json_decode(
                app()['Illuminate\Contracts\Http\Kernel']->handle(
                    Request::create("/api/simulate/catalog/category/".$slug, 'GET')
                )->content(), true
            );
            // Check if category exist and update/create.
            $category = $this->categoryModel->updateOrCreate(
                ["slug" => $slug],
                $apiResponse["data"]
            );

            // For each page, add a new schedule to import products.
            $totalPages = ceil($apiResponse["data"]["total_products"] / CategoryModel::PAGINATE)+1;
            for ($i=1; $i < $totalPages; $i++) {
                // Get info from API (by the moment we get it from internal route).
                $apiResponse = json_decode(
                    app()['Illuminate\Contracts\Http\Kernel']->handle(
                        Request::create("/api/simulate/catalog/category/".$slug."/".$i, 'GET')
                    )->content(), true
                );

                $products = $apiResponse["data"]["products"];
                foreach ($products as $product) {
                    $apiResponse = json_decode(
                        app()['Illuminate\Contracts\Http\Kernel']->handle(
                            Request::create("/api/simulate/catalog/product/".$product["slug"]."/".$product["id"], 'GET')
                        )->content(), true
                    );

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
            }
        }
    }
}

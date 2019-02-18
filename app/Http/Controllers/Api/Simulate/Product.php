<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Goutte\Client;

class ApiSimulateProduct extends Controller
{
    const API_DOMAIN = "https://www.appliancesdelivered.ie";
    /**
     * Goutte\Client instance
     *
     * @var Goutte\Client
     */
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Client $client
    ){
        $this->client = $client;
    }

    /**
     * TODO: Write doc
     */
    public function index(Request $request, $slug, $id)
    {
        $crawler = $this->client->request('GET', self::API_DOMAIN."/".$slug."/".$id); // Get info from URL
        if( !$crawler ) $response->json([ // Show error if product does not exist.
            "code" => "404",
            "message" => "There is no product with this id",
            "data" => ""
        ]);

        $data = [];
        // Get basic info from product
        $product = [
            "slug" => str_slug($crawler->filter('#product-title')->text()),
            "title" => $crawler->filter('#product-title')->text(),
            "id" => $id,
            "imported_url" => self::API_DOMAIN."/".$slug."/".$id,
            "description" => $crawler->filter('#product-lg-overview')->html(),
            "price" => $crawler->filterXpath("//meta[@property='product:price:amount']")->extract(['content'])[0],
            "in_stock" => $crawler->filterXpath("//meta[@property='product:availability']")->extract(['content'])[0],
            "meta_title" => $crawler->filterXpath("//title")->text(),
            "meta_description" => $crawler->filterXpath("//meta[@name='description']")->extract(['content'])[0],
        ];

        // Get conditional info from product
        $previousPrice = $crawler->filter('.price-previous .price-value');
        if( $previousPrice->count() > 0 ) {
            preg_match('/([\d|.]+)/', $previousPrice->text(), $matches);
            $product = array_merge($product, ["price_previous" => (float)$matches[1]]);
        }
        $brand = $crawler->filterXpath("//meta[@property='product:brand']");
        if( $brand->count() > 0 ) {
            $brand = $brand->extract('content');
            $product = array_merge($product, ["brand" => $brand[0]]);
        }
        $features = $crawler->filter('ul.features li');
        if( $features->count() > 0 ) {
            $features = $features->each(function ($node, $id){
                return trim($node->text());
            });
            $product = array_merge($product, ["key_features" => serialize($features)]);
        }
        $data["product"] = $product;

        // Categories from product
        $data["categories"] = [];
        $counter = 1;
        $categoryParent = "";
        $categoriesFound = $crawler->filter('.breadcrumb a');
        foreach ($categoriesFound as $category) {
            if( $counter == count($categoriesFound) ) continue; // The last one is the product it self
            $categoryTitle = $category->textContent;
            $data["categories"][] = [
                "title" => $categoryTitle,
                "slug" => str_slug($categoryTitle),
                "url" => $category->getAttribute('href'),
                "parent" => $categoryParent
            ];

            $categoryParent = str_slug($categoryTitle);
            $counter++;
        }

        // Images from product
        $data["images"] = [];
        $imagesFound = $crawler->filter('#product-gallery .product-img-container img');
        if( $imagesFound->count() > 0 ){
            $order = 0;
            foreach ($imagesFound as $image) {
                if( $image->getAttribute("src") != "") {
                    $data["images"][] = [
                        "thumbnail" => $image->getAttribute("src"),
                        "large" => $image->getAttribute("data-zoom-image"),
                        "order" => $order
                    ];
                    $order++;
                }
            }
        }

        // Videos from product
        $data["videos"] = [];
        $videosFound = $crawler->filter('#product-lg-videos .video-wrapper iframe');
        if( $videosFound->count() > 0 ){
            $order = 0;
            foreach ($videosFound as $video) {
                if( $video->getAttribute("src") != "") {
                    $data["videos"][] = [
                        "url" => $video->getAttribute("src"),
                        "order" => $order
                    ];
                    $order++;
                }
            }
        }

        return response()->json([
            "code" => "200",
            "message" => "Ok",
            "data" => $data
        ]);
    }
}

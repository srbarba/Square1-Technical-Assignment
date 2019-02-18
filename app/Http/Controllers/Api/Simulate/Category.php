<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Goutte\Client;

class ApiSimulateCategory extends Controller
{
    const API_DOMAIN = "https://www.appliancesdelivered.ie/search";
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
    public function index(Request $request, $slug, $page = null)
    {
        $url = self::API_DOMAIN."/".$slug;
        if( $page ) $url .= "?page=".$page;

        $crawler = $this->client->request('GET', $url); // Get info from URL
        if( !$crawler ) return $response->json([ // Show error if category does not exist.
            "code" => "404",
            "message" => "There is no category with this name/id",
            "data" => ""
        ]);

        // Collect the total of products within the category to go through the pagination.
        $total = $crawler->filter('.products-count');
        if( $total->count() < 0 ) return $response->json([ // Prevent errors if there is no total on page.
            "code" => "404",
            "message" => "There is no products in this category",
            "data" => ""
        ]);

        $getTotal = preg_match('/(\d+)/m', $total->text(), $matches);
        $totalProductsInCategory = $matches[0];

        $productsLink = $crawler->filter('.search-results-product .product-image a')->links();
        $products = [];
        foreach ($productsLink as $link) {
            $productURL = $link->getUri();
            preg_match('/\/(\d+)$/m', $productURL, $matches);
            $idProduct = $matches[1];
            preg_match('/'.str_replace("/", "\/", "https://www.appliancesdelivered.ie").'\/(.*)\/\d+$/m', $productURL, $matches);
            $slugProduct = $matches[1];
            $url = $link->getUri();

            $products[] = [
                "id" => $idProduct,
                "slug" => $slugProduct,
                "url" => $url
            ];
        }

        return response()->json([
            "code" => "200",
            "message" => "Ok",
            "data" => [
                "title" => $crawler->filter('.breadcrumb li.active a')->text(),
                "meta_title" => $crawler->filterXpath("//title")->text(),
                "meta_description" => $crawler->filterXpath("//meta[@name='description']")->extract(['content'])[0],
                "slug" => $slug,
                "total_products" => $totalProductsInCategory,
                "imported_url" => self::API_DOMAIN."/".$slug,
                "products" => $products
            ]
        ]);
    }
}

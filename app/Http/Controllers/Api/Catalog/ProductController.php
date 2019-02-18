<?php

namespace App\Http\Controllers\Api\Catalog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\Product;
use App\Models\User\User;

class ProductController extends Controller
{

    /**
     * TODO: Write doc
     */
    public function getAll(Request $request)
    {
        if( $request->query("order") != "" ) {
            $orderBy = explode(":", $request->query("order"));
            $product = Product::with('images')->orderBy($orderBy[0], $orderBy[1])->paginate(12);
        } else {
            $product = Product::with('images')->paginate(12);
        }

        if( count($product) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available products',
                'data' => ""
            ], 200); // This should send a custom code
        }

        return response([
            'status' => 'success',
            'data' => $product
        ], 200);

    }

    /**
     * TODO: Write doc
     */
    public function getByUser(Request $request, $id)
    {
        $user = User::find($id);

        if( $request->query("order") != "" ) {
            $orderBy = explode(":", $request->query("order"));
            $products = $user->products()->with('images')->orderBy($orderBy[0], $orderBy[1])->paginate(12);
        } else {
            if( $request->query("page") != "" ) {
                $products = $user->products()->with('images')->paginate(12);
            }else{
                $userProducts = $user->products()->select('catalog_product.product_id')->get();
                $products = [];
                foreach ($userProducts as $prd) {
                    $products[] = $prd->product_id;
                }
            }
        }

        if( count($products) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available products',
                'data' => ""
            ], 200);
        }

        return response([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    /**
     * TODO: Write doc
     */
    public function getById(Request $request, $id)
    {
        $product = Product::find($id);

        if( count($product) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available product with id '.$id,
                'data' => ""
            ], 200); // This should send a custom code
        }

        // Load Images and Videos
        $product->images;
        $product->videos;

        return response([
            'status' => 'success',
            'data' => $product
        ], 200);
    }

    /**
     * TODO: Write doc
     */
    public function getBySlug(Request $request, $slug)
    {
        $product = Product::where("slug", $slug)->first();

        if( count($product) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available product',
                'data' => ""
            ], 200); // This should send a custom code
        }

        $product->categories;
        $product->images;
        $product->videos;

        return response([
            'status' => 'success',
            'data' => $product
        ], 200);
    }

}

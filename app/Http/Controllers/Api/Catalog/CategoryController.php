<?php

namespace App\Http\Controllers\Api\Catalog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category\Category;

class CategoryController extends Controller
{

    /**
     * TODO: Write doc
     */
    public function getAll()
    {

        $category = Category::where('parent_id', NULL)->get();

        if( count($category) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available categories',
                'data' => ""
            ], 200); // This should send a custom code
        }

        return response([
            'status' => 'success',
            'data' => $category
        ], 200);

    }

    /**
     * TODO: Write doc
     */
    public function getById(Request $request, $id)
    {
        $category = Category::find($id);

        if( count($category) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available category with id '.$id,
                'data' => ""
            ], 200); // This should send a custom code
        }

        return response([
            'status' => 'success',
            'data' => $category
        ], 200);

    }

    /**
     * TODO: Write doc
     */
    public function getBySlug(Request $request, $slug)
    {
        $category = Category::where("slug", $slug)->withCount("products")->first();

        if( count($category) <= 0){
            return response([
                'status' => 'error',
                'message' => 'There is not available category with slug '.$slug,
                'data' => ""
            ], 200); // This should send a custom code
        }

        if( $request->query("order") != "" ) {
            $orderBy = explode(":", $request->query("order"));
            $products = $category->products()->with('images')->orderBy($orderBy[0], $orderBy[1])->paginate(12);
            $category->products = $products;
        } else {
            $category->products->with('images')->paginate(12);
        }

        return response([
            'status' => 'success',
            'data' => $category
        ], 200);
    }

}

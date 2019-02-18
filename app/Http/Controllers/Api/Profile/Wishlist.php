<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\Product;
use App\Models\User\User;

class WishlistController extends Controller
{

    /**
     * TODO: Write doc
     */
    public function addProduct($user, $product)
    {
        $user = User::find($user);
        $product = Product::find($product);

        $user->addProduct($product);

        return response([
            'status' => 'success',
            'data' => ""
        ], 200);
    }

    /**
     * TODO: Write doc
     */
    public function removeProduct($user, $product)
    {
        $user = User::find($user);

        $user->products()->detach($product);

        return response([
            'status' => 'success',
            'data' => ""
        ], 200);
    }

}

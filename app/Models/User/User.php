<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Catalog\Product\Product;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get products into user wishlist.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "catalog_product_wishlist", "id", "product_id" );
    }

    /**
     * Add product to wishlist
     *
     * @return App\Models\User\User
     */
    public function addProduct($product)
    {
        if( !$this->products()->find($product->product_id) ){
            $this->products()->save($product);
        }
        return $this;
    }
}

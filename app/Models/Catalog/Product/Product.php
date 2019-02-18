<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Catalog\Category\Category;
use App\Models\Catalog\Product\ProductImage;
use App\Models\Catalog\Product\ProductVideo;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_product';

    /**
     * The primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = "product_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'title', 'slug', 'price_previous', 'price', 'description', 'brand', 'in_stock', 'key_features',
        'meta_title', 'meta_description', 'meta_description', 'meta_keywords',
        'imported_id', 'imported_url'
    ];

    /**
     * Cast unserialize for key_features field
     */
    public function getKeyFeaturesAttribute($value)
    {
        return isset($value) ? unserialize($value) : $value;
    }

    /**
     * Get categories from product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, "catalog_category_product", "product_id", "category_id");
    }

    /**
     * Get categories from product.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, "catalog_product_wishlist", "product_id", "id");
    }

    /**
     * Get images from product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Get videos from product.
     */
    public function videos()
    {
        return $this->hasMany(ProductVideo::class, 'product_id');
    }

    /**
     * Add category to current Product checking if relationship exists.
     *
     * @return App\Models\Catalog\Product\Product
     */
    public function addCategory($category)
    {
        if( !$this->categories()->find($category->category_id) ){
            $this->categories()->save($category);
        }
        return $this;
    }
}

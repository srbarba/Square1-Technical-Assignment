<?php

namespace App\Models\Catalog\Category;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\Product\Product;

class Category extends Model
{
    const PAGINATE = 20;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_category';

    /**
     * The primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = "category_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'parent_id', 'title', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'total_products', 'imported_url', 'imported_at'
    ];

    /**
     * Get the products with the category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "catalog_category_product", "category_id", "product_id");
    }

    /**
     * Get categories into the category.
     */
    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'category_id');
    }

    /**
     * Get parent category from the category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

}

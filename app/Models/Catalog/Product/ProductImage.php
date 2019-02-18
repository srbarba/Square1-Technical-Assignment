<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_product_image';

    /**
     * The primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = "image_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_id', 'product_id', 'thumbnail', 'large', 'order'
    ];

    /**
     * Get product with the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalog_product_video';

    /**
     * The primary key in the table.
     *
     * @var string
     */
    protected $primaryKey = "video_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id', 'product_id', 'url', 'order'
    ];

    /**
     * Get product with the video.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

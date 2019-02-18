<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product', function (Blueprint $table) {

            $table->increments('product_id');
            $table->integer('imported_id')->nullable(false)->index();
            $table->string('title')->nullable(false);
            $table->string('slug')->nullable(false)->unique();
            $table->float('price_previous', 8, 6)->nullable(true);
            $table->float('price', 8, 6)->nullable(true);
            $table->string('description')->nullable(true);
            $table->integer('in_stock')->nullable(true);
            $table->string('brand')->nullable(true);
            $table->string('key_features')->nullable(true);
            $table->string('meta_title')->nullable(true);
            $table->string('meta_description')->nullable(true);
            $table->string('meta_keywords')->nullable(true);
            $table->string('imported_url')->nullable(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product');
    }
}

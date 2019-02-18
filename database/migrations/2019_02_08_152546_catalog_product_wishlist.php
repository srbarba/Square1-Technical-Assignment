<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogProductWishlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_wishlist', function (Blueprint $table) {

            $table->integer('id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();

            $table->foreign('product_id')
                ->references('product_id')->on('catalog_product')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['id', 'product_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product_wishlist');
    }
}

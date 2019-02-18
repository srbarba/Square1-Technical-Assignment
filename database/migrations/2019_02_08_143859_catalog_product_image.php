<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogProductImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_image', function (Blueprint $table) {
            $table->increments('image_id');
            $table->integer('product_id')->nullable(true)->unsigned()->index();
            $table->string('thumbnail')->nullable(false)->index();
            $table->string('large')->nullable(true);
            $table->integer('order')->nullable(true)->unsigned()->index();
            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')->on('catalog_product')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_product_image');
    }
}

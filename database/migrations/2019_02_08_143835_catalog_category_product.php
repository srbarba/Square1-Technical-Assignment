<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogCategoryProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_category_product', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->foreign('category_id')
                ->references('category_id')->on('catalog_category')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')
                ->references('product_id')->on('catalog_product')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['category_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_category_product');
    }
}

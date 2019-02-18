<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_category', function (Blueprint $table) {
            $table->increments('category_id');
            $table->integer('parent_id')->unsigned()->nullable(true)->index();
            $table->string('title')->nullable(false);
            $table->string('slug')->nullable(false)->unique();
            $table->string('meta_title')->nullable(true);
            $table->string('meta_description')->nullable(true);
            $table->string('meta_keywords')->nullable(true);
            $table->integer('total_products')->nullable(true);
            $table->string('imported_url')->nullable(true);
            $table->timestamp('imported_at')->nullable(true);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('category_id')->on('catalog_category')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_category');
    }
}

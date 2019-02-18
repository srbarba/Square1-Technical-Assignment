<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogProductVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_product_video', function (Blueprint $table) {
            $table->increments('video_id');
            $table->integer('product_id')->nullable(true)->unsigned()->index();
            $table->string('url')->nullable(false)->index();
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
        Schema::dropIfExists('catalog_product_video');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id');
            $table->bigInteger('product_id');
            $table->integer('position')->nullable();
            $table->string('alt')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->text('src')->nullable();
            $table->text('variant_ids')->nullable();
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
        Schema::dropIfExists('product_images');
    }
}

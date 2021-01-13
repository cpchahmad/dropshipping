<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_products', function (Blueprint $table) {
            $table->bigInteger('id');
//            $table->bigInteger('shopify_id');

            $table->string('title')->nullable();
            $table->text('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('handle')->nullable();
            $table->string('published_at')->nullable();
            $table->string('template_suffix')->nullable();
            $table->string('published_scope')->nullable();
            $table->text('tags')->nullable();
            $table->longText('variants')->nullable();
            $table->longText('options')->nullable();
            $table->longText('images')->nullable();
            $table->longText('image')->nullable();
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
        Schema::dropIfExists('shopify_products');
    }
}

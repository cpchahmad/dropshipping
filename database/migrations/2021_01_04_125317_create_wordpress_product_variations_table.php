<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordpressProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordpress_product_variations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wordpress_variation_id')->nullable();
            $table->bigInteger('wordpress_product_id')->nullable();
            $table->string('shop_id')->nullable();
            $table->text('description')->nullable();
            $table->text('permalink')->nullable();
            $table->string('sku')->nullable();
            $table->string('price')->nullable();
            $table->string('regular_price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('date_on_sale_from')->nullable();
            $table->string('date_on_sale_to')->nullable();
            $table->string('on_sale')->nullable();
            $table->string('status')->nullable();
            $table->string('purchasable')->nullable();
            $table->string('virtual')->nullable();
            $table->string('downloadable')->nullable();
            $table->text('downloads')->nullable();
            $table->integer('download_limit')->nullable();
            $table->integer('download_expiry')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->string('manage_stock')->nullable();
            $table->string('stock_quantity')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('backorders')->nullable();
            $table->string('backorders_allowed')->nullable();
            $table->string('backordered')->nullable();
            $table->string('weight')->nullable();
            $table->text('dimensions')->nullable();
            $table->integer('shipping_class_id')->nullable();
            $table->integer('menu_order')->nullable();
            $table->text('image')->nullable();
            $table->text('attributes')->nullable();
            $table->text('meta_data')->nullable();
            $table->text('links')->nullable();
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
        Schema::dropIfExists('wordpress_product_variations');
    }
}

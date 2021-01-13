<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordpressLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordpress_line_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('shop_id')->nullable();
            $table->bigInteger('wordpress_order_id')->nullable();
            $table->bigInteger('wordpress_product_id')->nullable();
            $table->bigInteger('wordpress_variation_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('sku')->nullable();
            $table->text('meta_data')->nullable();
            $table->text('taxes')->nullable();
            $table->string('image')->nullable();
            $table->string('total')->nullable();
            $table->string('total_tax')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('subtotal_tax')->nullable();
            $table->string('tax_class')->nullable();
            $table->string('vendor')->nullable();
            $table->integer('fulfillable_quantity')->nullable();
            $table->string('fulfillment_status')->nullable();
            $table->string('fulfillment_response')->nullable();
            $table->text('properties')->nullable();
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
        Schema::dropIfExists('wordpress_line_items');
    }
}

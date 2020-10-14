<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('variant_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('sku')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('price')->nullable();
            $table->bigInteger('shopify_order_id')->nullable();
            $table->string('vendor')->nullable();
            $table->integer('fulfillable_quantity')->nullable();
            $table->string('fulfillment_status')->nullable();
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
        Schema::dropIfExists('line_items');
    }
}

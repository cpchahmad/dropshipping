<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFulfillmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_fulfillments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('tracking_company')->nullable();
            $table->bigInteger('shopify_order_id')->nullable();
            $table->string('shipping_price_usd')->nullable();
            $table->string('shipping_price_rmb')->nullable();
            $table->string('shipping_currency')->nullable();
            $table->string('fulfillment_response')->nullable();
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
        Schema::dropIfExists('order_fulfillments');
    }
}

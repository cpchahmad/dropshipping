<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('total_line_item_price')->nullable();
            $table->string('total_price')->nullable();
            $table->string('name')->nullable();
            $table->string('currency')->nullable();
            $table->longText('customer')->nullable();
            $table->string('financial_status')->nullable();
            $table->string('fulfillment_status')->nullable();
            $table->longText('line_items')->nullable();
            $table->longText('shipping_address')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->longText('billing_address')->nullable();
            $table->longText('shipping_lines')->nullable();
            $table->longText('fulfillments')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('shopify_orders');
    }
}

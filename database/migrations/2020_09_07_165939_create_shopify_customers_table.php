<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_customers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('orders_count')->nullable();
            $table->text('state')->nullable();
            $table->string('total_spent')->nullable();
            $table->string('last_order_id')->nullable();
            $table->text('note')->nullable();
            $table->boolean('verified_email')->nullable();;
            $table->string('phone')->nullable();
            $table->string('tags')->nullable();
            $table->string('last_order_name')->nullable();
            $table->string('currency')->nullable();
            $table->longText('addresses')->nullable();
            $table->longText('default_address')->nullable();
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
        Schema::dropIfExists('shopify_customers');
    }
}

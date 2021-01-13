<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordpressOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordpress_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wordpress_order_id')->nullable();
            $table->string('shop_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('number')->nullable();
            $table->string('order_key')->nullable();
            $table->string('created_via')->nullable();
            $table->string('version')->nullable();
            $table->string('status')->nullable();
            $table->string('currency')->nullable();
            $table->string('discount_total')->nullable();
            $table->string('discount_tax')->nullable();
            $table->string('shipping_total')->nullable();
            $table->string('shipping_tax')->nullable();
            $table->string('cart_tax')->nullable();
            $table->string('total')->nullable();
            $table->string('total_tax')->nullable();
            $table->string('prices_include_tax')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('customer_ip_address')->nullable();
            $table->string('customer_user_agent')->nullable();
            $table->string('customer_note')->nullable();
            $table->text('billing')->nullable();
            $table->text('shipping')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_title')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('date_paid')->nullable();
            $table->string('date_completed')->nullable();
            $table->string('cart_hash')->nullable();
            $table->text('meta_data')->nullable();
            $table->text('line_items')->nullable();
            $table->text('tax_lines')->nullable();
            $table->text('shipping_lines')->nullable();
            $table->text('fee_lines')->nullable();
            $table->text('coupon_lines')->nullable();
            $table->text('refunds')->nullable();
            $table->string('currency_symbol')->nullable();
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
        Schema::dropIfExists('wordpress_orders');
    }
}

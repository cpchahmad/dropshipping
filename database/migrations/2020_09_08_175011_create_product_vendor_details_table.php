<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVendorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_vendor_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_product_id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('cost')->nullable();
            $table->string('moq')->nullable();
            $table->string('leads_time')->nullable();
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
        Schema::dropIfExists('product_vendor_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordpressProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordpress_products', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('wordpress_product_id')->nullable();
            $table->string('shop_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('permalink')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('featured')->nullable();
            $table->string('catalog_visibility')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->nullable();
            $table->float('price')->nullable();
            $table->float('regular_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->string('date_on_sale_from')->nullable();
            $table->string('date_on_sale_from_gmt')->nullable();
            $table->string('date_on_sale_to')->nullable();
            $table->string('date_on_sale_to_gmt')->nullable();
            $table->text('price_html')->nullable();
            $table->string('on_sale')->nullable();
            $table->string('purchasable')->nullable();
            $table->float('total_sales')->nullable();
            $table->string('virtual')->nullable();
            $table->string('downloadable')->nullable();
            $table->string('downloads')->nullable();
            $table->string('download_limit')->nullable();
            $table->string('download_expiry')->nullable();
            $table->string('external_url')->nullable();
            $table->string('button_text')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->string('manage_stock')->nullable();
            $table->string('stock_quantity')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('backorders')->nullable();
            $table->string('backorders_allowed')->nullable();
            $table->string('backordered')->nullable();
            $table->string('sold_individually')->nullable();
            $table->string('weight')->nullable();
            $table->text('dimensions')->nullable();
            $table->string('shipping_required')->nullable();
            $table->string('shipping_taxable')->nullable();
            $table->string('shipping_class')->nullable();
            $table->bigInteger('shipping_class_id')->nullable();
            $table->string('reviews_allowed')->nullable();
            $table->float('average_rating')->nullable();
            $table->integer('rating_count')->nullable();
            $table->text('related_ids')->nullable();
            $table->text('upsell_ids')->nullable();
            $table->text('cross_sell_ids')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('purchase_note')->nullable();
            $table->text('categories')->nullable();
            $table->text('tags')->nullable();
            $table->text('images')->nullable();
            $table->text('attributes')->nullable();
            $table->text('default_attributes')->nullable();
            $table->text('variations')->nullable();
            $table->text('grouped_products')->nullable();
            $table->integer('menu_order')->nullable();
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
        Schema::dropIfExists('wordpress_products');
    }
}

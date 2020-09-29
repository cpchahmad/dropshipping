<?php

namespace App\Console\Commands;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopsController;
use App\ShopifyOrder;
use Illuminate\Console\Command;

class ShopifyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopifycron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Order and product every minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $api = ShopsController::config();
        $admin = new AdminController();

        $orders = $api->rest('GET', '/admin/orders.json', null, [], true);

        foreach ($orders['body']['container']['orders'] as $order) {
            $admin->createOrder($order);
        }

        $products = $api->rest('GET', '/admin/products.json', null, [], true);

        foreach ($products['body']['container']['products'] as $product) {
            $admin->createProduct($product);
        }

    }
}

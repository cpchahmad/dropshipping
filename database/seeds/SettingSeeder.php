<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::create([
            'name' => 'Wholesale Phone Accessories',
            'email' => 'admin@WholesalePhoneAccessories.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin@Wholesale'),
        ]);


        \App\Shop::create([
            'shop_domain' => 'elightin.myshopify.com',
            'api_key' => ' 0bf5a0bc1a50d0c57a43a7097f791dfc',
            'api_secret' => '4836695a8deb3db20f50ce41e02aca00',
            'api_version' => '2020-01',
            'api_password' => '13e58d41bef4e7386d3f49144f3c65c4'
        ]);

        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'shipping_team']);
        \Spatie\Permission\Models\Role::create(['name' => 'outsource_team']);

        $user->assignRole('admin');

//        \App\Http\Controllers\AdminController::storeProducts();
//        \App\Http\Controllers\AdminController::storeOrders();
//        \App\Http\Controllers\AdminController::storeCustomers();

    }
}

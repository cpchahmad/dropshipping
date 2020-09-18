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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
        ]);

        \App\Shop::create([
            'shop_domain' => 'the-dev-studio.myshopify.com',
            'api_key' => 'be92661ed04c9b5da934aff114b739e9',
            'api_secret' => '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',
            'api_version' => '2020-01',
            'api_password' => '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'
        ]);

        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'shipping_team']);
        \Spatie\Permission\Models\Role::create(['name' => 'outsource_team']);

        $user->assignRole('admin');

        \App\Http\Controllers\AdminController::storeProducts();
        \App\Http\Controllers\AdminController::storeOrders();
        \App\Http\Controllers\AdminController::storeCustomers();

    }
}

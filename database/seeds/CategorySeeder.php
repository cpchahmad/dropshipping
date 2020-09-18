<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Category::create([
            'category_name' => 'Sports'
        ]);

        App\Category::create([
            'category_name' => 'Casual'
        ]);

        App\Category::create([
            'category_name' => 'Fuzzy'
        ]);

        App\Category::create([
            'category_name' => 'Dress'
        ]);

        App\Category::create([
            'category_name' => 'Wedding'
        ]);
    }
}

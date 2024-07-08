<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Faker\Factory as Faker;
use App\Models\ProductDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $faker = Faker::create();

        User::create([
            'name' => 'Emam hossain',
            'email' => 'emam@gmail.com',
            'password' => 'password',
        ]);


        $productCategories = [
            'Electronics',
            'Home Appliances',
            'Fashion',
            'Books',
            'Health & Beauty',
            'Sports & Outdoors',
            'Toys & Games',
            'Automotive',
            'Jewelry',
            'Furniture',
            'Pet Supplies',
            'Groceries',

        ];

        foreach ($productCategories as &$name) {
            Category::create([
                'categoryName' => $name,
                'categoryImg' => $faker->imageUrl(),
            ]);
        }




        for ($i = 0; $i < 10; $i++) {
            Brand::create([
                'brandName' => $faker->company,
                'brandImg' => $faker->imageUrl(),
            ]);
        }


        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'category_id' => Category::inRandomOrder()->pluck('id')->first(),
                'brand_id' => Brand::inRandomOrder()->pluck('id')->first(),
                'title' => $faker->sentence,
                'short_des' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 10, 100),
                'discount' => $faker->boolean,
                'discount_price' => $faker->randomFloat(2, 5, 50),
                'image' => $faker->imageUrl(),
                'stock' => $faker->boolean,
                'star' => $faker->numberBetween(1, 5),
                'remark' => $faker->randomElement(['popular', 'new', 'top', 'special', 'trending', 'regular']),
            ]);
        }
        $products = Product::get();
        foreach ($products as $product) {
            ProductDetail::create([
                'img1' => $faker->imageUrl(),
                'img2' => $faker->imageUrl(),
                'img3' => $faker->imageUrl(),
                'img4' => $faker->imageUrl(),
                'des' => $faker->paragraph,
                'color' => "$faker->colorName,$faker->colorName,$faker->colorName",
                'size' => "sm,md,lg,xl",
                'product_id' => $product->id
            ]);

        }




    }
}

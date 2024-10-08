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

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ]);

        // for ($i = 0; $i < 10; $i++) {
        //     Product::create([
        //         'user_id' => $user->id,
        //         // 'created_by' => $user->id,
        //         // 'updated_by' => $user->id,
        //         'title' => $faker->sentence,
        //         'short_des' => $faker->paragraph,
        //         'price' => $faker->randomFloat(2, 10, 100),
        //         'image' => $faker->imageUrl(),
        //         'product_quantity' => $faker->numberBetween(1, 200),
        //     ]);
        // }


    }
}

<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $favorites = [
            ["user_id" => 1, "product_name" => "Some sort of old Cheese"],
            ["user_id" => 1, "product_name" => "Surimi"],
            ["user_id" => 1, "product_name" => "Granolla"],

        ];

        foreach ($favorites as $favorite) {
            Favorite::create(array(
                'user_id' => $favorite['user_id'],
                'product_id' => Product::where('name', $favorite['product_name'])->first()->id
            ));
        }
    }
}

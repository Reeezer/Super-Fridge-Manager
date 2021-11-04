<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ["name" => "Some sort of old Cheese", "ean_code" => '3228023170068', 'category_name' => "Cheese"],
            ["name" => "Surimi", "ean_code" => '3302747010029', 'category_name' =>"Fish"],
            ["name" => "Knacki", "ean_code" => '7613034926814', 'category_name' => 'Meat'],
            ["name" => "Granolla", "ean_code" => '7622210601988', 'category_name' => 'Biscuits'],
        ];

        foreach ($products as $product){
            Product::create(array(
                'name' => $product["name"],
                'ean_code' => $product["ean_code"],
                'category_id' => Category::where('name', $product['category_name'])->first()->id
            ));
        }
    }
}

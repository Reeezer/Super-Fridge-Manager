<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $categories = [
            ["name" => "Vegetables", "expiration_days" => 14],
            ["name" => "Meat", "expiration_days" => 9],
            ["name" => "Fish", "expiration_days" => 6],
            ["name" => "Juice", "expiration_days" => 5],
            ["name" => "Biscuits", "expiration_days" => 730],
            ["name" => "Cheese", "expiration_days" => 30],
            ["name" => "Paste", "expiration_days" => 365],
        ];

        foreach ($categories as $category){
            Category::create(array(
                'name' => $category["name"],
                'expiration_days' => $category["expiration_days"]
            ));
        }
    }
}

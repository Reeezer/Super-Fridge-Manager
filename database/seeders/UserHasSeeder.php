<?php

namespace Database\Seeders;

use App\Models\UserHas;
use App\Models\Product;
use Illuminate\Database\Seeder;

class UserHasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_have = [
            ["user_id" => 1, "product_name" => "Some sort of old Cheese", "date" => date("Y-m-d"), 'quantity' => 42],
            ["user_id" => 1, "product_name" => "Surimi", "date" => date("Y-m-d"), "quantity" => 2],
            ["user_id" => 1, "product_name" => "Knacki", "date" => date("Y-m-d"), "quantity" => 7],
            ["user_id" => 1, "product_name" => "Granolla", "date" => date("Y-m-d"), "quantity" => 1],
            ["user_id" => 1, "product_name" => "Camembert", "date" => date("Y-m-d"), "quantity" => 3],
            ["user_id" => 2, "product_name" => "Jambon", "date" => date("Y-m-d"), "quantity" => 8],
            ["user_id" => 2, "product_name" => "Colin", "date" => date("Y-m-d"), "quantity" => 6],
            ["user_id" => 2, "product_name" => "Some sort of old Cheese", "date" => date("Y-m-d"), 'quantity' => 3]
        ];

        foreach ($users_have as $user_has) {
            UserHas::create(array(
                'user_id' => $user_has['user_id'],
                'product_id' => Product::where('name', $user_has['product_name'])->first()->id,
                'added_date' => $user_has['date'],
                'quantity' => $user_has['quantity']
            ));
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        User::factory(10)->create();
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
    }
}

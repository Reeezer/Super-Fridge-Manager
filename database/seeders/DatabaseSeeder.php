<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserHas;
use App\Models\Category;
use App\Models\Favorite;
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
        User::truncate();
        UserHas::truncate();
        Favorite::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserHasSeeder::class);
        $this->call(FavoriteSeeder::class);
    }
}

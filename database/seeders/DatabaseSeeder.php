<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Przykład seeda dla kategorii i marki
        \App\Models\Category::factory(9)->create();
        \App\Models\Brand::factory(9)->create();

        // Produkty powinny być tworzone po utworzeniu kategorii i marki
        \App\Models\Product::factory(50)->create();

    }
}

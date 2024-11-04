<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductCategory::truncate();
        Schema::enableForeignKeyConstraints();
        ProductCategory::insert(['id' => 1, 'name' => 'Aksesoris', 'description' => 'Deskripsi Aksesoris']);
        ProductCategory::insert(['id' => 2, 'name' => 'Laptop', 'description' => 'Deskripsi Laptop']);
        ProductCategory::insert(['id' => 3, 'name' => 'Printer', 'description' => 'Deskripsi Printer']);
        ProductCategory::insert(['id' => 4, 'name' => 'Networking', 'description' => 'Deskripsi Networking']);
        ProductCategory::insert(['id' => 5, 'name' => 'CCTV', 'description' => 'Deskripsi CCTV']);
    }
}

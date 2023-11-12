<?php

namespace Database\Seeders;

use App\Models\CostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        CostCategory::truncate();
        Schema::enableForeignKeyConstraints();

        CostCategory::insert(['id' => 1, 'name' => 'Listrik']);
        CostCategory::insert(['id' => 2, 'name' => 'Internet']);
        CostCategory::insert(['id' => 3, 'name' => 'Gaji Karyawan']);
    }
}

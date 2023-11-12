<?php

namespace Database\Seeders;

use App\Models\Cost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Cost::truncate();
        Schema::enableForeignKeyConstraints();

        Cost::insert(['id' => 1, 'category_id' => 1, 'description' => 'Listrik Oktober 2023', 'date' => '2023-10-15', 'amount' => '150000']);
        Cost::insert(['id' => 2, 'category_id' => 1, 'description' => 'Listrik November 2023', 'date' => '2023-11-15', 'amount' => '150000']);
    }
}

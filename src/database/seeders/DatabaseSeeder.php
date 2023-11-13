<?php

namespace Database\Seeders;

use App\Models\CashTransaction;
use App\Models\CashTransactionCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserGroupSeeder::class,
            UserSeeder::class,
            CostCategorySeeder::class,
            CostSeeder::class,
            CashAccountSeeder::class,
            CashTransactionCategorySeeder::class,
            CashTransactionSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}

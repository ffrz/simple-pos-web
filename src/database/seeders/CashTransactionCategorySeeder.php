<?php

namespace Database\Seeders;

use App\Models\CashTransactionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CashTransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        CashTransactionCategory::truncate();
        Schema::enableForeignKeyConstraints();

        CashTransactionCategory::insert(['id' => 1, 'name' => 'Saldo Awal']);
        CashTransactionCategory::insert(['id' => 2, 'name' => 'Penyesuaian Saldo']);
        CashTransactionCategory::insert(['id' => 3, 'name' => 'Biaya Operasional']);
        CashTransactionCategory::insert(['id' => 4, 'name' => 'Biaya Penyusutan']);
        CashTransactionCategory::insert(['id' => 5, 'name' => 'Penarikan Modal']);
        CashTransactionCategory::insert(['id' => 6, 'name' => 'Tambahan Modal']);
        CashTransactionCategory::insert(['id' => 7, 'name' => 'Pembelian']);
        CashTransactionCategory::insert(['id' => 8, 'name' => 'Penjualan']);
    }
}


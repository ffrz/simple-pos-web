<?php

namespace Database\Seeders;

use App\Models\CashAccount;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class CashAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        CashAccount::truncate();
        Schema::enableForeignKeyConstraints();

        CashAccount::insert(['id' => 1, 'name' => 'Kas Rumah', 'type' => CashAccount::ACCOUNT_TYPE_CASH, 'balance' => 1500000]);
        CashAccount::insert(['id' => 2, 'name' => 'Kas Toko', 'type' => CashAccount::ACCOUNT_TYPE_CASH, 'balance' => 500000]);
        CashAccount::insert(['id' => 3, 'name' => 'Rek Mandiri', 'type' => CashAccount::ACCOUNT_TYPE_BANK, 'balance' => 20000000]);
    }
}

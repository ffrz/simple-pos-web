<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Party::truncate();
        Schema::enableForeignKeyConstraints();

        Party::insert(['id' => 1, 'type' => Party::TYPE_CUSTOMER, 'name' => 'Ayat HTC', 'active' => true]);
        Party::insert(['id' => 2, 'type' => Party::TYPE_CUSTOMER, 'name' => 'Anadzsa', 'active' => true]);
        Party::insert(['id' => 3, 'type' => Party::TYPE_SUPPLIER, 'name' => 'Ica', 'active' => true]);
        Party::insert(['id' => 4, 'type' => Party::TYPE_SUPPLIER, 'name' => 'Calvin', 'active' => true]);
    }
}

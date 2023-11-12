<?php

namespace Database\Seeders;

use App\Models\UserGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        UserGroup::truncate();
        Schema::enableForeignKeyConstraints();

        UserGroup::insert(['id' => 1, 'name' => 'System Administrator']);
        UserGroup::insert(['id' => 2, 'name' => 'Maintainer']);
        UserGroup::insert(['id' => 11, 'name' => 'Owner']);
        UserGroup::insert(['id' => 12, 'name' => 'Administrator']);
        UserGroup::insert(['id' => 13, 'name' => 'Kasir']);
    }
}

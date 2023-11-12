<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::insert([
            'username' => 'superadmin',
            'password' => Hash::make('12345'),
            'is_active' => true,
            'is_admin' => true,
            'group_id' => 1,
        ]);
        User::insert([
            'username' => 'maintainer',
            'password' => Hash::make('12345'),
            'is_active' => true,
            'is_admin' => false,
            'group_id' => 2,
        ]);
        User::insert([
            'username' => 'owner',
            'password' => Hash::make('12345'),
            'is_active' => false,
            'is_admin' => true,
            'group_id' => 11,
        ]);
        User::insert([
            'username' => 'admin',
            'password' => Hash::make('12345'),
            'is_active' => true,
            'is_admin' => false,
            'group_id' => 12,
        ]);
        User::insert([
            'username' => 'kasir',
            'password' => Hash::make('12345'),
            'is_active' => false,
            'is_admin' => false,
            'group_id' => 13,
        ]);
    }
}

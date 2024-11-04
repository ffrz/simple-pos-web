<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'admin' => true,
            'active' => true,
        ]);

        $this->call([
            // UserGroupSeeder::class,
            // UserSeeder::class,
            // PartySeeder::class,
            // ServiceOrderSeeder::class,
            ProductCategorySeeder::class,
            // ProductSeeder::class,
            // ExpenseCategorySeeder::class,
        ]);
    }
}

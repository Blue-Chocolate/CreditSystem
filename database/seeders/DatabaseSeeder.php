<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // 3 Admins
    \App\Models\User::factory()->admin()->create([
        'name' => 'Super Admin',
        'email' => 'admin1@example.com',
    ]);

    \App\Models\User::factory()->admin()->create([
        'name' => 'Admin Two',
        'email' => 'admin2@example.com',
    ]);

    \App\Models\User::factory()->admin()->create([
        'name' => 'Admin Three',
        'email' => 'admin3@example.com',
    ]);

    // 17 Normal Users
    \App\Models\User::factory(17)->create();

    // Other factories
    \App\Models\CreditPackage::factory(5)->create();
    \App\Models\Category::factory(5)->create();
    \App\Models\Product::factory(20)->create();
    \App\Models\Purchase::factory(30)->create();
    \App\Models\Redemption::factory(15)->create();
}
}

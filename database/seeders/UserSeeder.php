<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 500,
            'credit_points' => 100,
            'reward_points' => 50,
            'first_login' => false,
        ]);
        $user1->assignRole('user');
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 300,
            'credit_points' => 50,
            'reward_points' => 20,
            'first_login' => false,
        ]);
        $user2->assignRole('user');
        $user3 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 700,
            'credit_points' => 150,
            'reward_points' => 80,
            'first_login' => false,
        ]);
        $user3->assignRole('user');
        $user4 = User::create([
            'name' => 'Bob Brown',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 600,
            'credit_points' => 120,
            'reward_points' => 70,
            'first_login' => false,
        ]);
        $user4->assignRole('user');
        $user5 = User::create([
            'name' => 'Charlie Davis',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 800,
            'credit_points' => 180,
            'reward_points' => 90,
            'first_login' => false,
        ]);
        $user5->assignRole('user');
        $user6 = User::create([
            'name' => 'Eve Wilson',
            'email' => 'eve@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 900,
            'credit_points' => 200,
            'reward_points' => 100,
            'first_login' => false,
        ]);
        $user6->assignRole('user');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'credit_balance' => 1000,
            'credit_points' => 500,
            'reward_points' => 300,
            'first_login' => false,
        ]);
        $admin->assignRole('admin');
    }

}

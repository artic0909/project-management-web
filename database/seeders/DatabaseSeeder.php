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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345'),
        ]);

        \App\Models\Sale::create([
            'name' => 'Sales Executive',
            'email' => 'sale@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345'),
        ]);

        \App\Models\Developer::create([
            'name' => 'Developer',
            'email' => 'developer@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345'),
        ]);
    }
}

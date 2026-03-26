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

        // Seed Statuses
        $statusTypes = [
            ['name' => 'Pending', 'type' => 'order', 'color' => '#f59e0b'],
            ['name' => 'Processing', 'type' => 'order', 'color' => '#6366f1'],
            ['name' => 'Completed', 'type' => 'order', 'color' => '#10b981'],
            ['name' => 'Cancelled', 'type' => 'order', 'color' => '#ef4444'],
            ['name' => 'Paid', 'type' => 'payment', 'color' => '#10b981'],
            ['name' => 'Partially Paid', 'type' => 'payment', 'color' => '#6366f1'],
            ['name' => 'Due', 'type' => 'payment', 'color' => '#f59e0b'],
            ['name' => 'Converted', 'type' => 'lead', 'color' => '#10b981'],
        ];

        foreach ($statusTypes as $st) {
            \App\Models\Status::create($st);
        }

        // Seed Services
        $services = ['Website Design', 'App Development', 'Digital Marketing', 'SEO', 'Social Media'];
        foreach ($services as $s) {
            \App\Models\Service::create(['name' => $s]);
        }
    }
}

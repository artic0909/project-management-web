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

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );

        \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
            ]
        );

        \App\Models\Sale::firstOrCreate(
            ['email' => 'sale@mail.com'],
            [
                'name' => 'Sales Executive',
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
            ]
        );

        \App\Models\Developer::firstOrCreate(
            ['email' => 'developer@mail.com'],
            [
                'name' => 'Developer',
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
            ]
        );

        // Seed Statuses
        $statusTypes = [
            ['name' => 'Pending', 'type' => 'order'],
            ['name' => 'Processing', 'type' => 'order'],
            ['name' => 'Completed', 'type' => 'order'],
            ['name' => 'Cancelled', 'type' => 'order'],
            ['name' => 'Paid', 'type' => 'payment'],
            ['name' => 'Partially Paid', 'type' => 'payment'],
            ['name' => 'Due', 'type' => 'payment'],
            ['name' => 'Converted', 'type' => 'lead'],
            ['name' => 'In Progress', 'type' => 'lead'],
            ['name' => 'Follow Up', 'type' => 'lead'],
            ['name' => 'Lost', 'type' => 'lead'],
        ];

        foreach ($statusTypes as $st) {
            \App\Models\Status::firstOrCreate(['name' => $st['name'], 'type' => $st['type']], $st);
        }

        // Seed Services
        $services = ['Website Design', 'App Development', 'Digital Marketing', 'SEO', 'Social Media'];
        foreach ($services as $s) {
            \App\Models\Service::firstOrCreate(['name' => $s], ['created_by' => '1']);
        }

        // Seed Sources
        $sources = ['Facebook', 'Google', 'LinkedIn', 'Referral', 'Direct'];
        foreach ($sources as $src) {
            \App\Models\Source::firstOrCreate(['name' => $src], ['created_by' => '1']);
        }

        // Seed Campaigns
        $campaigns = ['Spring Promotion', 'Summer Sale', 'Brand Awareness', 'Direct Reach'];
        foreach ($campaigns as $cmp) {
            \App\Models\Campaign::firstOrCreate(['name' => $cmp], ['created_by' => '1']);
        }
    }
}

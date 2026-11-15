<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@direktori.ac.id'],
            [
                'name' => 'Admin Direktori Kampus',
                'password' => 'password123',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->call(OpportunitySeeder::class);
    }
}

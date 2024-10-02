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
            // Use the User factory to create a test user with all necessary fields
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'institution_name' => 'Test Institution',  // Add institution name
                'institution_number' => '12345678',        // Add institution number
                'password' => bcrypt('password'),           // Password hashed
                'phone_number' => '+962123456789',         // Valid phone number format
                'user_role_id' => 10,                       // Set role ID
                'user_status_id' => 2,                      // Set status ID
            ]);
        }
}

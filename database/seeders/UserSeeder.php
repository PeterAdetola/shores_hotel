<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Don't forget to import Hash
use App\Models\User; // Don't forget to import your User model
use Carbon\Carbon; // Used for setting email_verified_at

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if a user with a specific username or email already exists to prevent duplicates
        if (!User::where('email', 'peteradetola@gmail.com')->exists()) {
            User::create([
                'name' => 'Peter Adetola', // Replace with your desired name
                'username' => 'pter', // Replace with your desired username
                'email' => 'peteradetola@gmail.com', // Replace with your desired email
                'password' => Hash::make('peter.com'), // Replace with your desired password
                'email_verified_at' => Carbon::now(), // This bypasses email verification
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info('User "peteradetola" seeded successfully!');
        } else {
            $this->command->info('User "peteradetola" already exists. Skipping seeding.');
        }
    }
}

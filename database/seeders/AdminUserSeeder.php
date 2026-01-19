<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@holaconnect.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@holaconnect.com',
                'password' => Hash::make('password123'), // Change this to a secure password
                'phone' => '+353 1 234 5678',
                'psp_number' => null,
                'taxi_driver_id' => null,
                'user_type' => 0, // 0 = Admin
                'document_dashboard_path' => null,
                'document_identity_path' => null,
                'document_payment_receipt_path' => null,
                'payment_type' => 'new_payment',
                'terms_agreed' => true,
                'share_certificate_agreed' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@holaconnect.com');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}

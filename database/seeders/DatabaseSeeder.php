<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'staff', 'description' => 'Staff members with limited admin access'],
            ['name' => 'volunteer', 'description' => 'Registered volunteers'],
            ['name' => 'user', 'description' => 'Regular users/customers'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }

          $rohan = User::firstOrCreate(
            ['email' => 'vishal@gmail.com'],
            [
                'name' => 'Vishal',
                'username' => 'vishal',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        if ($adminRole && !$rohan->roles()->where('role_id', $adminRole->id)->exists()) {
            $rohan->roles()->attach($adminRole->id);
        }

        // Create sample staff user
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff Member',
                'username' => 'staff',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign staff role
        $staffRole = Role::where('name', 'staff')->first();
        if ($staffRole && !$staff->roles()->where('role_id', $staffRole->id)->exists()) {
            $staff->roles()->attach($staffRole->id);
        }

        // Create sample customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Regular Customer',
                'username' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign customer role
        $customerRole = Role::where('name', 'user')->first();
        if ($customerRole && !$customer->roles()->where('role_id', $customerRole->id)->exists()) {
            $customer->roles()->attach($customerRole->id);
        }

        // add ngo records
        $this->call(NgoSeeder::class);
    }
}

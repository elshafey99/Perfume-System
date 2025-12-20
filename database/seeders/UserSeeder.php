<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first role (should be created by RoleSeeder)
        $role = Role::first();

        // Create Admin User
        User::create([
            'image' => 'uploads/images/image.png',
            'name' => 'مدير النظام',
            'email' => 'admin@perfume.com',
            'phone' => '01000000001',
            'password' => Hash::make('password'),
            'position' => 'مدير عام',
            'type' => 'admin',
            'role_id' => $role?->id,
            'status' => true,
        ]);

        // Create Employee User
        User::create([
            'image' => 'uploads/images/image.png',
            'name' => 'موظف',
            'email' => 'employee@perfume.com',
            'phone' => '01000000002',
            'password' => Hash::make('password'),
            'position' => 'موظف مبيعات',
            'type' => 'employee',
            'role_id' => null,
            'status' => true,
        ]);
    }
}

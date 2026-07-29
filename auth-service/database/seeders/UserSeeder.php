<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'nama'     => 'Super Admin',
            'email'    => 'superadmin@umb.ac.id',
            'password' => 'superadmin123',
            'role'     => 'super_admin',
        ]);

        // Admin Keuangan
        User::create([
            'nama'     => 'Admin Keuangan',
            'email'    => 'keuangan@umb.ac.id',
            'password' => 'password123',
            'role'     => 'admin_keuangan',
        ]);

    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'test12120121@gmail.com',
                'email' => 'test12120121@gmail.com',
                'password' => 'test12120121@gmail.com'
            ],
        ];

        foreach($admins as $admin)
        {
            $adminUser = User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => $admin['password'],
                'email_verified_at' => now(),
            ]);

            $adminUser->assignRole('Super Admin');
            $adminUser->assignRole('Admin');
        }
    }
}

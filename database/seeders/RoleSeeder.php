<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'admin',
            'staf',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create test user
        $users = [
            [
                'email' => 'frans22si@mahasiswa.pcr.ac.id',
                'name' => 'Frans',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'email' => 'nanda22si@mahasiswa.pcr.ac.id',
                'name' => 'Habibi',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($user->wasRecentlyCreated || !$user->hasAnyRole($roles)) {
                $user->assignRole($roles);
            }
        }
    }
}

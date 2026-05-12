<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'        => 'System Administrator',
                'email'       => 'admin@gmail.com',
                'password'    => Hash::make('Password1'),
                'employee_id' => 'EMP001',
                'department'  => 'Administration',
                'designation' => 'System Administrator',
                'status'      => 'active',
                'meal_active' => true,
            ]
        );
        $admin->syncRoles(['super_admin']);

        // Manager
        $manager = User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name'        => 'Meal Manager',
                'email'       => 'manager@gmail.com',
                'password'    => Hash::make('Password1'),
                'employee_id' => 'EMP002',
                'department'  => 'Operations',
                'designation' => 'Meal Manager',
                'status'      => 'active',
                'meal_active' => true,
            ]
        );
        $manager->syncRoles(['manager']);

        // Staff members
        $staffMembers = [
            ['name' => 'Alice Johnson',  'email' => 'alice@gmail.com',  'employee_id' => 'EMP003', 'department' => 'Engineering'],
            ['name' => 'Bob Williams',   'email' => 'bob@gmail.com',    'employee_id' => 'EMP004', 'department' => 'Marketing'],
            ['name' => 'Carol Davis',    'email' => 'carol@gmail.com',  'employee_id' => 'EMP005', 'department' => 'Finance'],
            ['name' => 'David Brown',    'email' => 'david@gmail.com',  'employee_id' => 'EMP006', 'department' => 'HR'],
            ['name' => 'Eve Martinez',   'email' => 'eve@gmail.com',    'employee_id' => 'EMP007', 'department' => 'Engineering'],
        ];

        foreach ($staffMembers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'    => Hash::make('Password1'),
                    'designation' => 'Staff Member',
                    'status'      => 'active',
                    'meal_active' => true,
                ])
            );
            $user->syncRoles(['staff']);
        }
    }
}

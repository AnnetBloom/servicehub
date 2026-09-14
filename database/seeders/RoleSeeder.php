<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $roles = [
            'Manager' => 'Responsible for overseeing and coordinating the work of a team or department.',
            'Administrator' => 'Handles administrative tasks, manages user accounts, and ensures smooth operations.',
            'Engineer' => 'Designs, develops, and maintains technical solutions or systems.',
            'Leader' => 'Provides guidance, direction, and motivation to a group or organization.'
        ];

        foreach ($roles as $role => $description) {
            Role::firstOrCreate(
                ['name' => $role],
                ['description' => $description]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Manager' => [
                'user.view',
                'ticket.view',
                'ticket.create',
                'ticket.assign',
                'ticket.change_status',
                'company.view',
                'company.create',
                'company.update',
                'site.view',
                'site.create',
                'site.update',
                'device.view',
                'device.create',
                'device.change_status',
                'contract.view',
                'contract.create',
                'contract.update',
                'comment.view',
                'comment.create',   
            ],
            'Leader' => [
                'user.view',
                'ticket.view',
                'ticket.create',
                'ticket.assign',
                'ticket.change_status',
                'company.view',
                'site.view',
                'device.view',  
                'device.create',
                'device.change_status',
                'contract.view',
                'comment.view',
                'comment.create',

            ],
            'Administrator' => [
                'user.view',
                'user.create',
                'user.update',
                'ticket.view',
                'ticket.create',
                'ticket.assign',
                'ticket.change_status',
                'company.view',
                'company.create',
                'company.update',
                'site.view',
                'site.create',
                'site.update',
                'device.view',
                'device.create',
                'device.change_status',
                'comment.view',
                'comment.create',
                'contact.view',
                'contact.create',
                'contact.update'

            ],
            'Engineer' => [
                'user.view',
                'ticket.view',
                'ticket.change_status',
                'company.view',
                'site.view',
                'device.view',
                'device.create',
                'device.change_status',
                'contract.view',
                'comment.view',
                'comment.create',

            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::where('name', $roleName)->firstOrFail();

            $permsIds = Permission::whereIn('name', $perms)
                ->pluck('id')
                ->toArray();
                
            $role->permissions()->sync($permsIds);
        }
    }
}


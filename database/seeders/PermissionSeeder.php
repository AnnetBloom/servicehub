<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [ 
            'user.view' ,
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
            'contract.view',
            'contract.create',
            'contract.update',
            'contact.view',
            'contact.create',
            'contact.update',
            'comment.view',
            'comment.create',   
        ];

        foreach ($permissions as $v) {
            Permission::firstOrCreate(['name' => $v]);
        }
    }
}

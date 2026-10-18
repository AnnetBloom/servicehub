<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Site;
use \App\Models\DeviceType;
use \App\Models\Device;
use \App\Models\DeviceModel;

/**DatabaseSeeder у нас сейчас отвечает за базовые справочные данные — роли и permissions.
 *  А тестовые данные будем создавать фабриками отдельно в tinker, когда понадобится. */

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

         // Выполнить 1 раз в tinker

        //Company::factory(5)->create();

        /*$contacts = Contact::factory()
            ->count(3)
            ->forCompany([
                'name' => 'Ruecker-Kihn',
            ])
            ->create();*/

        /*$company = Company::find(1);

        Contact::factory()
            ->for($company)
            ->create();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);*/

        // Выполнить 1 раз в tinker

        //Site::factory()->create();

      /* $company = Company::first();

        Site::factory()
            ->for($company)
            ->create();*/

            

        //DeviceType::factory(10)->create();
        //DeviceModel::factory(10)->create();
        Device::factory(5)->create();

       User::factory(10)->create();
    }
}

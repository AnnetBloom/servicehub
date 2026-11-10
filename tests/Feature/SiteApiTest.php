<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Site;

class SiteApiTest extends TestCase
{
    use RefreshDatabase;
   
    public function test_index(): void
    {
        $company = Company::factory()->create();

        Site::factory()->count(2)->create([
            'company_id' => $company->id
        ]);

        $response = $this->getJson('/api/sites');
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 
                        'name', 
                        'address', 
                        'geo_data', 
                        'company_id', 
                        'company' => [
                            'id', 
                            'name',
                            'website'
                        ]
                    ],
                ],
            ])
               
            ->assertJsonPath('data.0.company.name', $company->name)
        ;
    }

    public function test_store(): void
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/sites', [
            'name' => 'Test Site',
            'address' => '123 Test St',
            'geo_data' => null,
            'company_id' => $company->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Test Site')
            ->assertJsonPath('data.address', '123 Test St')
            ->assertJsonPath('data.company_id', $company->id)
            ->assertJsonPath('data.company.name', $company->name);

        $this->assertDatabaseHas('sites', [
            'name' => 'Test Site',
            'address' => '123 Test St',
            'geo_data' => null,
            'company_id' => $company->id,
        ]);
       
    }

    public function test_store_fails_when_company_does_ot_exist(): void
    {
        $response = $this->postJson('/api/sites', [
            'name' => 'Test Site',
            'address' => '123 Test St',
            'company_id' => 99999,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id']);

        $this->assertDatabaseCount('sites', 0);
    }

    public function test_show(): void{
        $company = Company::factory()->create();

        $site = Site::factory()->create([
            'name' => 'Test Site',
            'address' => '123 Test St',
            'company_id' => $company->id,
        ]);

        $response = $this->getJson("/api/sites/{$site->id}");
        $response->assertOk()
            ->assertJsonPath('data.name', 'Test Site')
            ->assertJsonPath('data.address', '123 Test St')
            ->assertJsonPath('data.company_id', $company->id)
            ->assertJsonPath('data.company.name', $company->name);
    }

    public function test_update(): void
    {
        $company = Company::factory()->create();

        $site = Site::factory()->create([
            'name' => 'Test Site',
            'address' => '123 Test St',
            'company_id' => $company->id,
        ]);

        $response = $this->patchJson("/api/sites/{$site->id}", [
            'name' => 'updated name',           
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'updated name')
            ->assertJsonPath('data.address', '123 Test St');

        $this->assertDatabaseHas('sites', [
            'id' => $site->id,
            'name' => 'updated name',
        ]);
    }

    public function test_update_fails_when_company_does_not_exist(): void
    {
        $site = Site::factory()->create();

        $response = $this->patchJson("/api/sites/{$site->id}", [
            'company_id' => 99999,
        ]);

        $response->assertUnprocessable()
        ->assertJsonValidationErrors(['company_id']);

        $this->assertDatabaseHas('sites', [
            'id' => $site->id,
            'company_id' => $site->company_id,
        ]);
    }

    public function test_destroy(): void
    {
        $site = Site::factory()->create();

        $response = $this->deleteJson("/api/sites/{$site->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('sites', [
            'id' => $site->id,
        ]);
    }
}

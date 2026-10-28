<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_creating_company_without_name_returns_422(): void
    {
        $response = $this->postJson('/api/companies', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_creating_company_returns_201_and_company_data(): void
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Automated Test Company',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Automated Test Company')
            ->assertJsonPath('data.website', null)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'website'],
            ]);
    }

    public function test_show_returns_404_when_company_does_not_exist(): void
    {
        $response = $this->getJson('/api/companies/999999');

        $response->assertNotFound();
    }

    public function test_it_rejects_an_invalid_website_when_creating_a_company(): void
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'website' => 'invalid-url',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['website']);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Contact;
class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    //  Checks that the API returns a list of contacts with their associated company data.
    public function test_get_list(): void
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()
            ->create([
                'company_id' => $company->id,
            ]);

        $response = $this->getJson('/api/contacts');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', $contact->name)
            ->assertJsonPath('data.0.company.name', $company->name)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 
                        'name', 
                        'position', 
                        'phone', 
                        'email', 
                        'company_id', 
                        'company' => [
                            'id', 
                            'name', 
                            'website'
                        ]
                    ],
                ],
            ]);
        

           

    }

    // проверять создание контакта через POST /api/contacts.
    public function test_store_contact(): void
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/contacts', [
            'name' => 'John Doe',
            'position' => 'Manager',
            'phone' => '1234567890',
            'email' => 'john.doe@example.com',
            'company_id' => $company->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.position', 'Manager')
            ->assertJsonPath('data.phone', '1234567890')
            ->assertJsonPath('data.email', 'john.doe@example.com')
            ->assertJsonPath('data.company_id', $company->id)
            ->assertJsonStructure([
                'data' => [
                    'id', 
                    'name', 
                    'position', 
                    'phone', 
                    'email', 
                    'company_id', 
                    'company' => [
                        'id', 
                        'name', 
                        'website'
                    ]
                ],
            ]);

            $this->assertDatabaseHas('contacts', [
                'id' => $response->json('data.id'),
                'name' => 'John Doe',
                'position' => 'Manager',
                'phone' => '1234567890',
                'email' => 'john.doe@example.com',
                'company_id' => $company->id,
            ]);
    }

    public function test_store_contact_with_non_existing_company(): void
    {
        $response = $this->postJson("/api/contacts", [
            'name' => 'Mary Smith',
            'position' => 'Developer',
            'phone' => '1234567890',
            'email' => 'mary.smith@example.com',
            'company_id' => 999999, // Assuming this company ID does not exist
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id']);

        $this->assertDatabaseMissing("contacts", [
            'email' => 'mary.smith@example.com'
        ]);

    }

    public function test_show_contact(): void 
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertOk() // утверждает, что ответ имеет код 200 состояния HTTP – OK:
                 ->assertJsonPath('data.name', $contact->name)
                 ->assertJsonPath('data.email', $contact->email)
                 ->assertJsonPath('data.company.name', $company->name)
                 ->assertJsonStructure([
                    'data' => [
                        'id', 
                        'name', 
                        'position', 
                        'phone', 
                        'email', 
                        'company_id', 
                        'company' => [
                            'id', 
                            'name', 
                            'website'
                        ]
                    ],
                 ]); 
    }

     // test PATCH request to update contact's position and verify the response and database update.
    public function test_update_contact(): void
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->patchJson("/api/contacts/{$contact->id}", [
            'position' => 'Updated Position',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.position', 'Updated Position')
            ->assertJsonPath('data.name', $contact->name)
            ->assertJsonPath('data.phone', $contact->phone)
            ->assertJsonPath('data.email', $contact->email)
            ->assertJsonPath('data.company_id', $company->id);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'position' => 'Updated Position',
            'name' => $contact->name,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'company_id' => $company->id,
        ]);
    }

    // PATCH request with an invalid email should return a validation error 422 
    public function test_invalid_update_email_returns_validation_error(): void
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()->create([
            'company_id' => $company->id,
        ]);

        $response  = $this->patchJson("/api/contacts/{$contact->id}", [
            'email' => 'invalid-email',
        ]);

        $response->assertUnprocessable()
          ->assertJsonValidationErrors(['email']);
    }

    // PATCH with unexisting company id should return validation errror 422
    public function test_update_unexisting_company(): void
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->patchJson("/api/contacts/{$contact->id}", [            
            'company_id' => 999999, // Assuming this company ID does not exist
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id']);
    }

    public function test_delete_contact(): void 
    {
        $company = Company::factory()->create();

        $contact = Contact::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertNoContent(); // status 204

        $this->assertSoftDeleted('contacts', [
            'id' => $contact->id,
        ]);
    }

    public function test_show_non_existing_contact(): void 
    {
        $response = $this->getJson("/api/contacts/999999");
        $response->assertNotFound();
    }

   
}

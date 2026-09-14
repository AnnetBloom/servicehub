<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company_id' => Company::factory(), // если company_id явно не задан, создай Company через её Factory
            'position' => fake()->randomElement(['IT Director', 'Procurement Manager', 'Account Manager', 'Accountant']), // it дирктор, менеджер по закупкам, Менеджер по работе с клиентами, бухгалтер
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
        ];
    }
}

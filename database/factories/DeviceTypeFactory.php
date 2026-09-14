<?php

namespace Database\Factories;

use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceType>
 */
class DeviceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Router',
                'Switch',
                'Firewall',
                'IP Phone',
                'Access Point',
                'Server',
                'Workstation',
                'Printer',
                'Storage Device',
                'Camera',
            ]),
            'description' => fake()->optional()->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\DeviceModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DeviceType;

/**
 * @extends Factory<DeviceModel>
 */
class DeviceModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_type_id' => DeviceType::query()->inRandomOrder()->value('id'), //DeviceType::factory(),

            'vendor' => fake()->randomElement([
                'Cisco', 
                'Juniper', 
                'HP', 
                'Dell', 
                'Netgear', 
                'Ubiquiti', 
                'MikroTik'
            ]),
            'name' => fake()->unique()->word(),

            'description' => fake()->optional()->sentence(),
        ];
    }
}

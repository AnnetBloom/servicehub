<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Site;
use App\Models\DeviceModel; 

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'device_model_id' => DeviceModel::query()->inRandomOrder()->value('id'), //DeviceModel::factory(),
            'name' => fake()->word(),
            'serial_number' => fake()->unique()->bothify('SN-########'),
            'status' => fake()->randomElement([
                'active', 
                'inactive', 
                'maintenance', 
                'removed'
            ]),
        ];
    }
}

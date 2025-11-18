<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition()
    {
        return [
            'campus_id' => Campus::factory(),
            'name' => $this->faker->company . ' Building',
            'is_active' => $this->faker->boolean(90), // 90% chance active
        ];
    }
}

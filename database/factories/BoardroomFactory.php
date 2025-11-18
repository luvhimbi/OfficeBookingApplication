<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class BoardroomFactory extends Factory
{


    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'capacity' => $this->faker->numberBetween(5, 50),
            'campus_id' => \App\Models\Campus::factory(),
            'building_id' => \App\Models\Building::factory(),
            'floor_id' => \App\Models\Floor::factory(),
            'is_active' => true,
        ];

    }
}

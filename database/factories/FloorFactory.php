<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Campus;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class FloorFactory extends Factory
{
    protected $model = Floor::class;

    public function definition()
    {
      return [
           'building_id' => Building::factory(),
           'name' => 'Floor ' . $this->faker->numberBetween(1, 20),
       ];
    }
}

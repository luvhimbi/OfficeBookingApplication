<?php

namespace Database\Factories;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class CampusFactory extends Factory
{

    protected $model = Campus::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Campus',
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'is_active' => $this->faker->boolean(90),
        ];
    }

}


<?php

namespace Database\Factories;

use App\Models\Invite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invite>
 */
class InviteFactory extends Factory
{
    protected $model = Invite::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'role' => $this->faker->randomElement(['admin', 'employee']), // adjust roles as needed
            'token' => Str::random(40),
            'used' => $this->faker->boolean(20), // 20% chance the invite is already used
        ];
    }
}


<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $roles = ['admin', 'employee'];
        $positions = ['Manager', 'Developer', 'Designer', 'Analyst', 'HR Officer', 'Receptionist', 'Cleaner', 'Technician', 'IT Support', 'Security Guard'];

        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'position' => $this->faker->randomElement($positions),
            'role' => $this->faker->randomElement($roles),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default test password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

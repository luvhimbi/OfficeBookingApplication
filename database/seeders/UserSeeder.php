<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(2)->state([
            'role' => 'admin',
            'position' => 'Administrator'
        ])->create();

        // Create 8 Employees
        User::factory()->count(8)->state([
            'role' => 'employee',
        ])->create();
    }
}

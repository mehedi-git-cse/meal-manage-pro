<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->phoneNumber(),
            'employee_id'       => 'EMP' . fake()->unique()->numerify('###'),
            'department'        => fake()->randomElement(['Engineering', 'Marketing', 'Finance', 'HR', 'Operations']),
            'designation'       => fake()->jobTitle(),
            'password'          => Hash::make('Password1'),
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
            'status'            => 'active',
            'meal_active'       => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\MealEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealEntryFactory extends Factory
{
    protected $model = MealEntry::class;

    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'meal_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'meal_type' => fake()->randomElement(['breakfast', 'lunch', 'dinner']),
            'quantity'  => fake()->randomElement([0.5, 1, 1.5, 2]),
            'note'      => fake()->optional(0.2)->sentence(),
            'is_guest'  => false,
            'status'    => 'approved',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => ['status' => 'pending']);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => ['user_id' => $user->id]);
    }

    public function forDate(string $date): static
    {
        return $this->state(fn(array $attributes) => ['meal_date' => $date]);
    }
}

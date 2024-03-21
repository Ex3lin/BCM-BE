<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'cost' => fake()->randomNumber(5, false),
            'type' => fake()->randomElement(['task', 'income', 'expense']),
            'status' => fake()->randomElement(['active', 'completed', 'aborted']),
            'deadline' => fake()->dateTime(),
            'submitted' => fake()->randomElement([null, fake()->dateTime()]),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => \Str::uuid(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'reference_code' => $this->faker->randomElement(['finance', 'health', 'education']),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
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
            'section_id' => Section::factory(),
            'question' => $this->faker->sentence,
            'question_type' => $this->faker->randomElement(['text', 'radio', 'checklist']),
            'reference_code' => $this->faker->randomElement(['finance', 'risk', 'growth']),
        ];
    }
}

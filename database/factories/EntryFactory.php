<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
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
            'entry_id' => Entry::factory(),
            'survey_id' => Survey::factory(),
            'participant_id' => Participant::factory(),
            'question_id' => Question::factory(),
            'option_id' => null, // Set dynamically based on question type
            'text_answer' => $this->faker->sentence,
        ];
    }
}

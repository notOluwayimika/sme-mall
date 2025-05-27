<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Entry;
use App\Models\Participant;
use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $participants = Participant::factory()->count(5)->create();
    $surveys = Survey::with('sections.questions.options')->get();

    foreach ($surveys as $survey) {
        foreach ($participants as $participant) {
            $entry = Entry::create([
                'uuid' => \Str::uuid(),
                'survey_id' => $survey->id,
                'participant_id' => $participant->id,
            ]);

            foreach ($survey->sections as $section) {
                foreach ($section->questions as $question) {
                    if ($question->question_type === 'text') {
                        Answer::create([
                            'uuid' => \Str::uuid(),
                            'entry_id' => $entry->id,
                            'survey_id' => $survey->id,
                            'participant_id' => $participant->id,
                            'question_id' => $question->id,
                            'text_answer' => 'Sample answer',
                        ]);
                    } else {
                        $option = $question->options->random();

                        Answer::create([
                            'uuid' => \Str::uuid(),
                            'entry_id' => $entry->id,
                            'survey_id' => $survey->id,
                            'participant_id' => $participant->id,
                            'question_id' => $question->id,
                            'option_id' => $option->id,
                        ]);
                    }
                }
            }
        }
    }
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Entry;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return Survey::withCount('sections')->with('sections.questions')->get();
    }

    public function show(Survey $survey)
    {
        $survey->load('sections.questions.options');
        return view('survey.show', compact('survey'));

    }

    public function create(){
        $surveys = Survey::all();
        return view('survey.create', compact('surveys'));
    }
    public function edit(Survey $survey){
        $survey->load('sections.questions.options');
        $allReferences = Question::distinct()->pluck('reference_code')->filter()->values();
        // dd($survey);
        return view('survey.edit', compact('survey', 'allReferences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'reference_code' => 'nullable|string',
        ]);

        $validated['uuid'] = \Str::uuid();

        return Survey::create($validated);
    }

    public function submitSurvey(Request $request, Survey $survey)
    {
        $participant = Participant::firstOrCreate([
            'email' => $request->participant_email,
        ], [
            'name' => $request->participant_name,
            'uuid' => \Str::uuid(),
        ]);

        $entry = Entry::create([
            'uuid' => \Str::uuid(),
            'survey_id' => $survey->id,
            'participant_id' => $participant->id,
        ]);

        foreach ($request->answers as $ans) {
            Answer::create([
                'uuid' => \Str::uuid(),
                'entry_id' => $entry->id,
                'survey_id' => $survey->id,
                'participant_id' => $participant->id,
                'question_id' => $ans['question_id'],
                'option_id' => $ans['option_id'] ?? null,
                'text_answer' => $ans['text_answer'] ?? null,
            ]);
        }

        return response()->json(['entry_id' => $entry->id]);
    }

    public function scoreEntry(Survey $survey, Entry $entry, SurveyScoringService $scoringService)
    {
        return $scoringService->calculateEntryScore($entry->load('answers.question', 'answers.option'));
    }
}


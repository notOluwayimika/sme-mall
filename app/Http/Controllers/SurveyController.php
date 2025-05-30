<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Entry;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Survey;
use App\Services\SurveyScoringService;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return Survey::withCount('sections')->with('sections.questions')->get();
    }

    public function show(Survey $survey)
    {
        $survey->load(['sections.questions.options', 'sections.questions.subquestions', 'entries.participant', 'entries.survey']);
        return view('survey.show', compact('survey'));
    }

    public function create()
    {
        $surveys = Survey::all();
        return view('survey.create', compact('surveys'));
    }
    public function update(Request $request, Survey $survey)
    {
        try {
            $survey->update($request->all());
            return back()->with('success', 'Survey Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function edit(Survey $survey)
    {
        $survey->load(['sections.questions.options', 'sections.questions.subquestions']);
        $allReferences = Question::distinct()->pluck('reference_code')->filter()->values();
        // dd($survey);
        return view('survey.edit', compact('survey', 'allReferences'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required',
                'description' => 'nullable',
                'reference_code' => 'nullable|string',
            ]);

            $validated['uuid'] = \Str::uuid();

            Survey::create($validated);
            return back()->with('success', 'Survey Created Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function submitSurvey(Request $request, Survey $survey)
    {
        // dd($request->all());
        $participant = Participant::firstOrCreate([
            'email' => $request->email,
        ], [
            'name' => $request->name,
            'uuid' => \Str::uuid(),
        ]);

        $entry = Entry::create([
            'uuid' => \Str::uuid(),
            'survey_id' => $survey->id,
            'participant_id' => $participant->id,
        ]);

        foreach ($request->answers as $questionId => $ans) {
            $question = Question::where('id', $questionId)->first();
            if ($ans) {
                if ($question->question_type == 'text') {
                    Answer::create([
                        'uuid' => \Str::uuid(),
                        'entry_id' => $entry->id,
                        'survey_id' => $survey->id,
                        'participant_id' => $participant->id,
                        'question_id' => $questionId,
                        'text_answer' => $ans ?? null,
                    ]);
                } else {
                    if (is_array($ans)) {
                        foreach ($ans as $ca) {
                            Answer::create([
                                'uuid' => \Str::uuid(),
                                'entry_id' => $entry->id,
                                'survey_id' => $survey->id,
                                'participant_id' => $participant->id,
                                'question_id' => $questionId,
                                'option_id' => $ca
                            ]);
                        }
                    } else {
                        Answer::create([
                            'uuid' => \Str::uuid(),
                            'entry_id' => $entry->id,
                            'survey_id' => $survey->id,
                            'participant_id' => $participant->id,
                            'question_id' => $questionId,
                            'option_id' => $ans
                        ]);
                    }
                }
            }
        }
        return back()->with('success', 'Survey Response Recorded Successfully');
        // return response()->json(['entry_id' => $entry->id]);
    }

    public function scoreEntry(Survey $survey, Entry $entry, SurveyScoringService $scoringService)
    {
        $scores = $scoringService->calculateEntryScore($entry->load('answers.question', 'answers.option'));
        $total = $scoringService->calculateSurveyTotalScore($survey);
        $entry->load('answers.question');
        $survey->load(['sections.questions.answers', 'sections.questions.subquestions']);
        // dd($scores, $total);
        // dd($scores, $entry, $survey, $total);
        return view('entries.show', compact('scores', 'entry', 'survey', 'total'));
    }

    public function destroy(Survey $survey)
    {
        try {
            $survey->delete();
            return redirect('/surveys/create')->with('success', 'Survey Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function take(Survey $survey)
    {
        $survey->load(['sections.questions.options', 'sections.questions.subquestions']);
        return view('survey.take', compact('survey'));
    }
}

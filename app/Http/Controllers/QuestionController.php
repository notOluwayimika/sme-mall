<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Section $section){
        try {
            $question = new Question();
            $question->section_id = $section->id;
            $question->question = 'New Question';
            $question->question_type = 'text';
            $question->uuid = \Str::uuid();
            $question->save();
            return back()->with('success', 'Question Created successfully')->with('scroll_to', 'question-'.$question->id);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

    }
    public function update(Request $request, Question $question){
        try {
            $question->load('options.answers');
            $question_type = $request->question_type;
            if($question->question_type === 'leading'){
                $subquestions = $question->subquestions;
                foreach($subquestions as $sub){
                    $sub->delete();
                }
            }
            if($question_type == 'text'){
                $question->question = $request->question;
                $question->reference_code = $request->reference_code;
                $question->question_type = $request->question_type;
                $question->save();
            } else {
                $question->question = $request->question;
                $question->reference_code = $request->reference_code;
                $question->question_type = $request->question_type;
                $question->save();
                $new_options = $request->options;
                $current_options = $question->options;
                foreach ($current_options as $option) {
                    $option->delete();
                }
                if($new_options){
                    foreach ($new_options as $option) {
                        $new_option = new Option();
                        $new_option->question_id = $question->id;
                        $new_option->option = $option['option'];
                        $new_option->weight = $option['weight'];
                        $new_option->uuid = \Str::uuid();
                        $new_option->save();
                    }
                }
            }
            return back()->with('success', 'Question Updated successfully')->with('scroll_to', 'question-'.$question->id);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage())->with('scroll_to', 'question-'.$question->id);
        }

    }

    public function destroyOption(Option $option){
        try {
            $option->delete();
            return back()->with('success', 'Option deleted successfully')->with('scroll_to', 'question-'.$option->question->id);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function createOption(Question $question){
        try {
            if($question->question_type == 'leading'){
                return back()->with('error', 'Leading Questions Can Only Have Two Options');
            } else {
                $option = new Option();
                $option->uuid = \Str::uuid();
                $option->question_id = $question->id;
                $option->option = 'New Option';
                $option->weight = 0;
                $option->save();
                return back()->with('success', 'Option added successfully')->with('scroll_to', 'question-'.$question->id);
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Question $question){
        try {
            if($question->parent_question_id){
                $parent_question = $question->parent_question_id;
                $question->delete();
                return back()->with('success', 'Question deleted successfully')->with('scroll_to', 'question-'.$parent_question->id);
            }
            $question->delete();
            return back()->with('success', 'Question deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function createFollowUp(Question $question){
        try {
            $followUp = new Question();
            $followUp->parent_question_id = $question->id;
            $followUp->question = 'New Follow Up Question';
            $followUp->question_type = 'text';
            $followUp->uuid = \Str::uuid();
            $followUp->save();
            return back()->with('success', 'Follow Up Question added successfully')->with('scroll_to', 'question-'.$followUp->id);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage())->with('scroll_to', 'question-'.$question->id);
        }

    }
}

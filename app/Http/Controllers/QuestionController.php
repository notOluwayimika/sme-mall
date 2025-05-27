<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function update(Question $question){

    }

    public function destroyOption(Option $option){
        try {
            $option->delete();
            return back()->with('success', 'Option deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function createOption(Question $question){
        try {
            $option = new Option();
            $option->uuid = \Str::uuid();
            $option->question_id = $question->id;
            $option->option = 'New Option';
            $option->weight = 0;
            $option->save();
            return back()->with('success', 'Option added successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

    }
}

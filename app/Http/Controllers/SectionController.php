<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Survey;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function update(Request $request, Section $section){
        try {
            $section->update($request->all());
            return back()->with('success', 'Section Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

    }

    public function store(Survey $survey){
        try {
            $section = new Section();
            $section->survey_id = $survey->id;
            $section->section_title = 'New Section';
            $section->uuid = \Str::uuid();
            $section->save();
            return back()->with('success', 'New Section Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Section $section){
        try {
            $section->delete();
            return back()->with('success', 'Section Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}

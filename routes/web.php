<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('surveys')->group(function () {
    Route::get('/', [SurveyController::class, 'index']);
    Route::get('/create', [SurveyController::class, 'create']);
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/take/{survey}', [SurveyController::class, 'take'])->name('surveys.take');
    Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::post('/', [SurveyController::class, 'store'])->name('surveys.store');
    Route::put('/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy']);

    Route::post('/{survey}/submit', [SurveyController::class, 'submitSurvey'])->name('surveys.submit');;
    Route::get('/{survey}/score/{entry}', [SurveyController::class, 'scoreEntry']);
});

Route::prefix('questions')->group(function () {
    Route::post('/create/{section}', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('questions.update');
    Route::delete('/options/{option}', [QuestionController::class, 'destroyOption'])->name('questions.options.destroy');
    Route::post('/options/{question}', [QuestionController::class, 'createOption'])->name('questions.options.create');
    Route::post('/add-follow-up/{question}', [QuestionController::class, 'createFollowUp'])->name('questions.followup.create');
});

Route::prefix('sections')->group(function(){
    Route::put('/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::post('/{survey}', [SectionController::class, 'store'])->name('sections.store');
});


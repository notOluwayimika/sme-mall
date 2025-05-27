<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('surveys')->group(function () {
    Route::get('/', [SurveyController::class, 'index']);
    Route::get('/create', [SurveyController::class, 'create']);
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::post('/', [SurveyController::class, 'store'])->name('surveys.store');
    Route::put('/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy']);

    Route::post('/{survey}/submit', [SurveyController::class, 'submitSurvey']);
    Route::get('/{survey}/score/{entry}', [SurveyController::class, 'scoreEntry']);
});

Route::prefix('questions')->group(function () {
    Route::put('/{question}', [QuestionController::class, 'update'])->name('questions.update');
});


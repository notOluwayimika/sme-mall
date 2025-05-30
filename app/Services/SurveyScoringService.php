<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\Survey;

class SurveyScoringService
{
    public function calculateEntryScore(Entry $entry): array
    {
        $totalScore = 0;
        $questionScores = [];
        $sectionScores = [];

        foreach ($entry->answers as $answer) {
            $question = $answer->question;

            if ($question->question_type === 'text') {
                $score = 0;
            } else {
                $score = $answer->option?->weight ?? 0;
            }

            $questionScores[$question->id] = ($questionScores[$question->id] ?? 0) + $score;
            if ($question->parent) {
                $sectionId = $question->parent->section_id;
            } else {
                $sectionId = $question->section_id;
            }
            $sectionScores[$sectionId] = ($sectionScores[$sectionId] ?? 0) + $score;
            $totalScore += $score;
        }

        return [
            'total_score' => $totalScore,
            'section_scores' => $sectionScores,
            'question_scores' => $questionScores,
        ];
    }

    public function calculateSurveyTotalScore(Survey $survey)
    {
        $totalSurveyScore = 0;
        $totalSectionScores = [];
        $totalQuestionScores = [];

        foreach ($survey->sections as $section) {
            $sectionScore = 0;

            foreach ($section->questions as $question) {
                $questionScore = 0;

                if ($question->question_type == 'radio') {
                    $questionScore = $question->options->max('weight') ?? 0;
                } elseif ($question->question_type == 'checklist') {
                    $questionScore = $question->options->sum('weight');
                } else {
                    $questionScore = 0; // Text type or other
                }

                $totalQuestionScores[$question->id] = $questionScore;
                $sectionScore += $questionScore;
                if ($question->subquestions) {
                    foreach ($question->subquestions as $sub) {
                        $subquestionScore = 0;
                        if ($sub->question_type == 'radio') {
                            $subquestionScore = $sub->options->max('weight') ?? 0;
                        } elseif ($sub->question_type == 'checklist') {
                            $subquestionScore = $sub->options->sum('weight');
                        } else {
                            $subquestionScore = 0; // Text type or other
                        }
                        $totalQuestionScores[$sub->id] = $subquestionScore;
                        $sectionScore += $subquestionScore;
                    }
                }
            }

            $totalSectionScores[$section->id] = $sectionScore;
            $totalSurveyScore += $sectionScore;
        }

        return [
            'total_survey_score' => $totalSurveyScore,
            'total_section_scores' => $totalSectionScores,
            'total_question_scores' => $totalQuestionScores
        ];
    }
}

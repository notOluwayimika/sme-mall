<?php

namespace App\Services;

use App\Models\Entry;

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
            $sectionId = $question->section_id;
            $sectionScores[$sectionId] = ($sectionScores[$sectionId] ?? 0) + $score;
            $totalScore += $score;
        }

        return [
            'total_score' => $totalScore,
            'section_scores' => $sectionScores,
            'question_scores' => $questionScores,
        ];
    }
}


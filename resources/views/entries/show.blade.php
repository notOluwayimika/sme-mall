<x-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-2xl font-bold mb-4">Survey Entry #{{ $entry->id }}</h1>

        <p class="mb-2"><strong>Survey:</strong> {{ $entry->survey->title }}</p>
        <p class="mb-2">
            {{ $scores['total_score'] }}/{{ $total['total_survey_score'] }}({{ number_format(($scores['total_score'] / $total['total_survey_score']) * 100, 2) }}%)
        </p>
        <p class="mb-2"><strong>Submitted at:</strong> {{ $entry->created_at->format('F j, Y h:i A') }}</p>


        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-4">Sections</h2>
            @foreach ($survey->sections as $section)
                <div class="mb-2 font-bold text-lg flex justify-between border-b border-gray-300">Section:
                    {{ $section->section_title }}
                    <div class="flex">
                        {{ $scores['section_scores'][number_format($section->id)] ?? 0 }}/{{ $total['total_section_scores'][number_format($section->id)] }}
                        @if (number_format($total['total_section_scores'][number_format($section->id)]) > 0)
                            ({{ number_format(($scores['section_scores'][number_format($section->id)] / $total['total_section_scores'][number_format($section->id)]) * 100, 2) }}%)
                        @endif
                    </div>
                </div>
                <div class="pl-8">
                    <h2 class="text-xl font-semibold mb-4">Questions</h2>
                    <div class="pl-8">
                        @foreach ($section->questions as $question)
                            <div class=" font-bold text-lg flex justify-between">Question:
                                {{ $question->question }}
                                <div class="flex">
                                    {{ $scores['question_scores'][number_format($question->id)] }}/{{ $total['total_question_scores'][number_format($question->id)] }}
                                </div>
                            </div>
                            <div class="text-gray-400">Question Type: {{ $question->question_type }}</div>
                            @foreach ($entry->answers as $answer)
                                @if ($answer->question_id == $question->id)
                                    <div class="my-2">
                                        <p class="font-semibold">{{ $answer->question->text }}</p>

                                        @if ($answer->option)
                                            <p class="text-gray-800">Answer: {{ $answer->option->option }}</p>
                                        @elseif ($answer->text_answer)
                                            <p class="text-gray-800">Answer: {{ $answer->text_answer }}</p>
                                        @else
                                            <p class="text-gray-500 italic">No answer given.</p>
                                        @endif
                                    </div>
                                    @if ($answer->option?->option)
                                        @if ($question->subquestions && $answer->option->option == 'Yes')
                                            <div class="pl-8">
                                                @foreach ($question->subquestions as $sub)
                                                    <div class=" font-bold text-lg flex justify-between">Question:
                                                        {{ $sub->question }}
                                                        <div class="flex">
                                                            {{ $scores['question_scores'][number_format($sub->id)] ?? 0 }}/{{ $total['total_question_scores'][number_format($sub->id)] ?? 0 }}
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-400">Question Type:
                                                        {{ $sub->question_type }}</div>
                                                    @foreach ($entry->answers as $answer)
                                                        @if ($answer->question_id == $sub->id)
                                                            <div class="my-2">
                                                                <p class="font-semibold">{{ $answer->question->text }}
                                                                </p>

                                                                @if ($answer->option)
                                                                    <p class="text-gray-800">Answer:
                                                                        {{ $answer->option->option }}</p>
                                                                @elseif ($answer->text_answer)
                                                                    <p class="text-gray-800">Answer:
                                                                        {{ $answer->text_answer }}</p>
                                                                @else
                                                                    <p class="text-gray-500 italic">No answer given.</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layout>

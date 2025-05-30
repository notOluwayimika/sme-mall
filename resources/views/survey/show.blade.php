<x-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- View Survey -->
        @if (isset($survey))
            <div class="bg-white p-6 rounded shadow mb-6">
                <h2 class="text-2xl font-bold mb-2">{{ $survey->title }}</h2>
                <p class="text-gray-600 mb-4">{{ $survey->description }}</p>
                <p class="text-sm text-gray-500 mb-6">Reference Code: {{ $survey->reference_code }}</p>

                @foreach ($survey->sections as $section)
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $section->title }}</h3>
                        <div class="space-y-4">
                            @foreach ($section->questions as $question)
                                <div class="bg-gray-50 p-4 rounded border">
                                    <p class="font-medium mb-1">{{ $question->question }}</p>
                                    @if ($question->question_type == 'text')
                                        <p class="text-sm text-gray-600">Text answer (subjective)</p>
                                    @elseif($question->question_type == 'radio' || $question->question_type == 'checklist')
                                        <ul class="list-disc ml-6 text-gray-700">
                                            @foreach ($question->options as $option)
                                                <li>{{ $option->option }} ({{ $option->weight }} pts)</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if ($question->subquestions->isNotEmpty())
                                        <div class="mt-3 pl-4 border-l-2 border-blue-400">
                                            <p class="text-sm font-semibold text-blue-700">Subquestions:</p>
                                            @foreach ($question->subquestions as $sub)
                                                <p class="text-sm ml-2">- {{ $sub->question }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-white p-6 rounded shadow mb-6">
                <h2 class="text-2xl font-bold mb-2">Entries</h2>
                <div class="space-y-4">
                    @foreach ($survey->entries as $entry)
                        <div class="bg-gray-50 font-semibold p-4 rounded border flex justify-between">
                            <div>
                                <p class="font-medium mb-1">{{ $entry->participant->name }}</p>
                                <p class="text-sm text-gray-400 mb-1">{{ $entry->created_at }}</p>
                            </div>
                            <div>
                                <a href="/surveys/{{ $survey->id }}/score/{{ $entry->id }}">View Entry</a>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-gray-600">No survey found.</p>
        @endif
    </div>

</x-layout>

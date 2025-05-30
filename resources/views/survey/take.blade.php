<x-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-2">{{ $survey->title }}</h1>
            <p class="text-gray-600 mb-6">{{ $survey->description }}</p>

            <form action="{{ route('surveys.submit', $survey) }}" method="POST">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name='name' class="border p-2 w-full mb-3" />
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name='email' class="border p-2 w-full mb-3" />
                @foreach ($survey->sections as $section)
                    <div class="mb-8 border-b pb-4">
                        <h2 class="text-xl font-semibold mb-3">{{ $section->section_title }}</h2>

                        @foreach ($section->questions as $question)
                            <div class="mb-6 sub-question-div">
                                <label class="block font-medium text-gray-800 mb-1">
                                    {{ $question->question }}
                                </label>

                                @if ($question->question_type === 'text')
                                    <input type="text" name="answers[{{ $question->id }}]"
                                        class="w-full border rounded px-3 py-2" />
                                @elseif($question->question_type === 'radio')
                                    @foreach ($question->options as $option)
                                        <label class="block text-gray-700">
                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                value="{{ $option->id }}" class="mr-2">
                                            {{ $option->option }}
                                        </label>
                                    @endforeach
                                @elseif($question->question_type === 'checklist')
                                    @foreach ($question->options as $option)
                                        <label class="block text-gray-700">
                                            <input type="checkbox" name="answers[{{ $question->id }}][]"
                                                value="{{ $option->id }}" class="mr-2">
                                            {{ $option->option }}
                                        </label>
                                    @endforeach
                                @elseif($question->question_type === 'leading')
                                    <div class="mb-6">
                                        @foreach ($question->options as $option)
                                            <div>
                                                <label class="block text-gray-700">
                                                    <input id='{{ $question->id }}' class="leading-question-input"
                                                        type="radio" name="answers[{{ $question->id }}][]"
                                                        value="{{ $option->id }}" class="mr-2">
                                                    {{ $option->option }}
                                                </label>
                                            </div>
                                        @endforeach

                                        <div class="mb-6 sub-questions hidden">
                                            @foreach ($question->subquestions as $subquestion)
                                                <label class="block font-medium text-gray-800 mb-1">
                                                    {{ $subquestion->question }}
                                                </label>
                                                @if ($subquestion->question_type === 'text')
                                                    <input type="text" name="answers[{{ $subquestion->id }}]"
                                                        class="w-full border rounded px-3 py-2 follow-up-answer" />
                                                @elseif($subquestion->question_type === 'radio')
                                                    @foreach ($subquestion->options as $option)
                                                        <label class="block text-gray-700">
                                                            <input class="follow-up-answer" type="radio"
                                                                name="answers[{{ $subquestion->id }}]"
                                                                value="{{ $option->id }}" class="mr-2">
                                                            {{ $option->option }}
                                                        </label>
                                                    @endforeach
                                                @elseif($subquestion->question_type === 'checklist')
                                                    @foreach ($subquestion->options as $option)
                                                        <label class="block text-gray-700">
                                                            <input class="follow-up-answer" type="checkbox"
                                                                name="answers[{{ $subquestion->id }}][]"
                                                                value="{{ $option->id }}" class="mr-2">
                                                            {{ $option->option }}
                                                        </label>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit Survey
                </button>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.leading-question-input').forEach(radio => {
            radio.addEventListener('change', function() {
                const followUpSection = this.closest('.sub-question-div').querySelector('.sub-questions');
                const label = this.closest('label');
                const fullText = label.textContent.trim();
                if (fullText === 'Yes') {
                    followUpSection.classList.remove('hidden');
                    const reset_follow_ups = followUpSection.querySelectorAll('.follow-up-answer');
                    reset_follow_ups.forEach(rfu => {
                        // rfu.required = true;
                    });
                } else {
                    followUpSection.classList.add('hidden');
                    const reset_follow_ups = followUpSection.querySelectorAll('.follow-up-answer');
                    reset_follow_ups.forEach(rfu => {
                        if (rfu.type === 'checkbox' || rfu.type === 'radio') {
                            rfu.checked = false;
                        } else {
                            rfu.value = '';
                        }
                        rfu.required = false;
                    });
                }
            })
        });
    </script>
</x-layout>

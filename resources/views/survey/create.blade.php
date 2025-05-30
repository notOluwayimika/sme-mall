<x-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Create Survey Form -->
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Create Survey</h2>
            <form method="POST" action="{{ route('surveys.store') }}">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input name="title" class="border p-2 w-full mb-3" placeholder="Survey Title" required />
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" class="border p-2 w-full mb-3" placeholder="Description"></textarea>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                <input name="reference_code" class="border p-2 w-full mb-3" placeholder="Reference Code" />
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Create Survey</button>
            </form>
        </div>

        <!-- List Surveys -->
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Existing Surveys</h2>
            @foreach ($surveys as $survey)
                <div class="border p-4 mb-3 rounded">
                    <h3 class="font-semibold text-lg">{{ $survey->title }}</h3>
                    <p>{{ $survey->description }}</p>
                    <a href="{{ route('surveys.show', $survey) }}" class="text-blue-600 underline">View</a>
                    <a href="{{ route('surveys.edit', $survey) }}" class="text-blue-600 underline">Edit</a>
                </div>
            @endforeach
        </div>

        <!-- Submit Survey -->
        @if (isset($activeSurvey))
            <div class="bg-white p-6 rounded shadow mb-6">
                <h2 class="text-xl font-bold mb-4">Submit Survey: {{ $activeSurvey->title }}</h2>
                <form method="POST" action="{{ route('surveys.submit', $activeSurvey) }}">
                    @csrf
                    <input name="participant_name" class="border p-2 w-full mb-3" placeholder="Your Name" required />
                    <input name="participant_email" type="email" class="border p-2 w-full mb-3"
                        placeholder="Your Email" required />

                    @foreach ($activeSurvey->sections as $section)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold">{{ $section->title }}</h3>
                            @foreach ($section->questions as $question)
                                <div class="mb-4">
                                    <label class="block font-medium">{{ $question->question }}</label>
                                    @if ($question->question_type == 'text')
                                        <textarea name="answers[{{ $question->id }}][text_answer]" class="border p-2 w-full"></textarea>
                                    @elseif($question->question_type == 'radio')
                                        @foreach ($question->options as $option)
                                            <label class="block">
                                                <input type="radio" name="answers[{{ $question->id }}][option_id]"
                                                    value="{{ $option->id }}" class="mr-1">
                                                {{ $option->option }} ({{ $option->weight }} pts)
                                            </label>
                                        @endforeach
                                    @elseif($question->question_type == 'checklist')
                                        @foreach ($question->options as $option)
                                            <label class="block">
                                                <input type="checkbox"
                                                    name="answers[{{ $question->id }}][option_ids][]"
                                                    value="{{ $option->id }}" class="mr-1">
                                                {{ $option->option }} ({{ $option->weight }} pts)
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <button class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                </form>
            </div>
        @endif
    </div>
</x-layout>

<x-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Edit Survey -->
        @if (isset($survey))
            <div class="bg-white p-6 rounded shadow mb-6">
                <h2 class="text-2xl font-bold mb-4">Edit Survey</h2>
                <form method="POST" action="{{ route('surveys.update', $survey) }}">
                    @csrf
                    @method('PUT')
                    <input name="title" class="border p-2 w-full mb-3" placeholder="Survey Title"
                        value="{{ $survey->title }}" required />
                    <textarea name="description" class="border p-2 w-full mb-3" placeholder="Description">{{ $survey->description }}</textarea>
                    <input name="reference_code" class="border p-2 w-full mb-3" placeholder="Reference Code"
                        value="{{ $survey->reference_code }}" />
                    <button class="bg-blue-500 text-white px-4 py-2 rounded mb-6">Update Survey</button>
                </form>

                <!-- Questions -->
                <div class="bg-gray-50 p-4 rounded shadow">
                    <h3 class="text-xl font-semibold mb-4">Questions</h3>
                    @foreach ($survey->sections as $section)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium mb-2">Section: {{ $section->title }}</h4>
                            @foreach ($section->questions as $question)
                                <form method="POST" action="{{ route('questions.update', $question) }}"
                                    class="mb-4 bg-white p-4 rounded border">
                                    @csrf
                                    @method('PUT')
                                    <input name="question" class="border p-2 w-full mb-2" placeholder="Question"
                                        value="{{ $question->question }}" required />

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Reference Code</label>
                                    <input name="reference_code" class="border p-2 w-full mb-2"
                                        placeholder="Reference Code" value="{{ $question->reference_code }}" required />

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                                    <select name="question_type" class="border p-2 w-full mb-3 question-type-select">
                                        <option value="text" @if ($question->question_type == 'text') selected @endif>Text
                                        </option>
                                        <option value="radio" @if ($question->question_type == 'radio') selected @endif>Radio
                                        </option>
                                        <option value="checklist" @if ($question->question_type == 'checklist') selected @endif>
                                            Checklist</option>
                                    </select>

                                    <div class="options-section @if ($question->question_type == 'text') hidden @endif">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Options</label>
                                        @foreach ($question->options as $option)
                                            <div class="flex items-center mb-2">
                                                <input name="options[{{ $option->id }}][option]"
                                                    class="border p-2 mr-2 w-1/2" value="{{ $option->option }}"
                                                    placeholder="Option" />
                                                <input name="options[{{ $option->id }}][weight]"
                                                    class="border p-2 w-1/4" value="{{ $option->weight }}"
                                                    placeholder="Weight" type="number" />
                                            </div>
                                        @endforeach
                                    </div>

                                    <button class="bg-green-500 text-white px-4 py-2 rounded">Update Question</button>
                                </form>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-gray-600">Survey not found.</p>
        @endif
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.question-type-select').forEach(select => {
                select.addEventListener('change', function() {
                    const optionsSection = this.closest('form').querySelector('.options-section');
                    if (this.value === 'text') {
                        optionsSection.classList.add('hidden');
                    } else {
                        optionsSection.classList.remove('hidden');
                    }
                });
            });
        </script>
    @endpush

</x-layout>

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
                    <button class="bg-blue-500 text-white px-4 py-2 rounded mb-6 cursor-pointer">Update Survey</button>
                </form>
            </div>
            <div class="bg-white p-6 rounded shadow mb-6">
                <!-- Questions -->
                <div class="bg-gray-50 p-4 rounded shadow">
                    <div class="flex justify-between">
                        <h3 class="text-xl font-semibold mb-4">Sections
                        </h3>
                        <form action="/sections/{{ $survey->id }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded text-md cursor-pointer">Add
                                Section</button>
                        </form>
                    </div>

                    @foreach ($survey->sections as $section)
                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary
                                class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                <span class="font-medium"> {{ $section->section_title }} </span>

                                <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="mb-6 px-4">
                                <div class="flex justify-between my-4">
                                    <form action="/sections/{{ $section->id }}" method="POST" class="flex">
                                        @csrf
                                        @method('put')
                                        <input type="text" name="section_title" class="border w-full px-4 py-2"
                                            placeholder="Question" value="{{ $section->section_title }}">
                                        <button
                                            class="bg-blue-500 text-white rounded mx-2 px-4 py-2 cursor-pointer">Update</button>
                                    </form>
                                    <div class="flex gap-4">

                                        <form action="/sections/{{ $section->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer">Delete
                                                Section</button>
                                        </form>
                                    </div>

                                </div>

                                <h3 class="font-semibold mb-4">Questions</h3>
                                @foreach ($section->questions as $question)
                                    <p>Question {{ $loop->index + 1 }}</p>
                                    <form method="POST" action="{{ route('questions.update', $question) }}"
                                        class="mb-4 bg-white p-4 rounded border">
                                        @csrf
                                        @method('PUT')
                                        <input name="question" class="border p-2 w-full mb-2" placeholder="Question"
                                            value="{{ $question->question }}" required />

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference
                                            Code</label>
                                        <input name="reference_code" class="border p-2 w-full mb-2"
                                            placeholder="Reference Code" value="{{ $question->reference_code }}"
                                            required />

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Question
                                            Type</label>
                                        <select name="question_type"
                                            class="border p-2 w-full mb-3 question-type-select">
                                            <option value="text" @if ($question->question_type == 'text') selected @endif>
                                                Text
                                            </option>
                                            <option value="radio" @if ($question->question_type == 'radio') selected @endif>
                                                Radio
                                            </option>
                                            <option value="checklist" @if ($question->question_type == 'checklist') selected @endif>
                                                Checklist</option>
                                            <option value="leading" @if ($question->question_type == 'leading') selected @endif>
                                                Leading</option>
                                        </select>

                                        <div class="options-section @if ($question->question_type == 'text') hidden @endif">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Options

                                                <button
                                                    onclick="document.getElementById('add-option-form-{{ $question->id }}').submit()"
                                                    id="add-option-button"
                                                    class="bg-green-500 text-white px-4 py-2 m-2 font-bold cursor-pointer"
                                                    type="button">+</button>
                                            </label>
                                            @foreach ($question->options as $option)
                                                <div class="flex items-center mb-2">
                                                    <input name="options[{{ $option->id }}][option]"
                                                        class="border p-2 mr-2 w-1/2" value="{{ $option->option }}"
                                                        placeholder="Option" />
                                                    <input name="options[{{ $option->id }}][weight]"
                                                        class="border p-2 w-1/4" value="{{ $option->weight }}"
                                                        placeholder="Weight" type="number" />
                                                    <button
                                                        onclick="document.getElementById('delete-option-form-{{ $question->id }}').submit()"
                                                        id="delete-option-button"
                                                        class="bg-red-500 text-white px-4 py-2 m-2 font-bold cursor-pointer"
                                                        type="button">-</button>

                                                </div>
                                            @endforeach
                                            <button type="submit"
                                                class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer">Update
                                                Question</button>
                                            <button type="button"
                                                class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer">Delete
                                                Question</button>
                                            @if ($question->question_type == 'leading')
                                                <button type="button"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded">Add
                                                    Follow Up Question</button>
                                            @endif
                                        </div>


                                    </form>
                                    <form id="add-option-form-{{ $question->id }}"
                                        action="/questions/options/{{ $question->id }}" method="POST" class="hidden">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                            class="bg-green-500 text-white px-4 py-2 m-2 font-bold cursor-pointer">+</button>
                                    </form>
                                    <form id="delete-option-form-{{ $question->id }}"
                                        action="/questions/options/{{ $option->id }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 m-2 font-bold cursor-pointer">-</button>
                                    </form>
                                @endforeach
                            </div>
                        </details>
                    @endforeach

                </div>
            </div>
    </div>
@else
    <p class="text-gray-600">Survey not found.</p>
    @endif
    </div>

    <script>
        document.querySelectorAll('.question-type-select').forEach(select => {
            select.addEventListener('change', function() {
                const optionsSection = this.closest('form').querySelector('.options-section');
                if (this.value === 'text') {
                    optionsSection.classList.add('hidden');
                } else if (this.value === 'leading') {
                    optionsSection.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 mb-1">Options</label>
                    <div class="flex items-center mb-2">
                                                <input name="options[0][option]" class="border p-2 mr-2 w-1/2"
                                                    value="Yes" placeholder="Option" />
                                                <input name="options[0][weight]" class="border p-2 w-1/4" value="0"
                                                    placeholder="Weight" type="number" />
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <input name="options[1][option]" class="border p-2 mr-2 w-1/2"
                                                    value="No" placeholder="Option" />
                                                <input name="options[1][weight]" class="border p-2 w-1/4" value="0"
                                                    placeholder="Weight" type="number" />
                                            </div>`;
                } else {
                    optionsSection.classList.remove('hidden');
                    optionsSection.innerHTML = `<label class="block text-sm font-medium text-gray-700 mb-1">Options</label>
                                            @foreach ($question->options as $option)
                                                <div class="flex items-center mb-2">
                                                    <input name="options[{{ $option->id }}][option]"
                                                        class="border p-2 mr-2 w-1/2" value="{{ $option->option }}"
                                                        placeholder="Option" />
                                                    <input name="options[{{ $option->id }}][weight]"
                                                        class="border p-2 w-1/4" value="{{ $option->weight }}"
                                                        placeholder="Weight" type="number" />
                                                </div>
                                            @endforeach`
                }
            });
        });
        setTimeout(() => {
            document.querySelector('#alert-border').classList.add('hidden')
        }, 5000);
    </script>


</x-layout>

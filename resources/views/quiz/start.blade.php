    @extends('layouts.pelamar') {{-- Atau layout publik yang sesuai --}}
    @section('title', 'Tes Bakat Minat')

    @section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 shadow-xl rounded-lg p-6 sm:p-8 md:p-10">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2 text-center">Tes Bakat Minat Dira</h1>
            <p class="text-slate-600 dark:text-slate-400 mb-8 text-center">
                Jawablah semua pertanyaan berikut dengan jujur sesuai dengan diri Anda. Tidak ada jawaban benar atau salah.
            </p>

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                    <p class="font-bold">Oops!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                    <p class="font-bold">Harap perbaiki error berikut:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if($questions->count() > 0)
                <form action="{{ route('quiz.submit') }}" method="POST">
                    @csrf
                    @foreach ($questions as $index => $question)
                        <fieldset class="mb-8 p-5 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm">
                            <legend class="px-2 font-semibold text-lg text-slate-800 dark:text-slate-200 mb-3">
                                Pertanyaan {{ $index + 1 }}:
                            </legend>
                            <p class="mb-4 text-slate-700 dark:text-slate-300">{{ $question->text }}</p>

                            @if($question->options->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($question->options as $option)
                                        <label for="option_{{ $option->id }}"
                                               class="flex items-center p-3 ps-4 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30 has-[:checked]:border-indigo-400 dark:has-[:checked]:border-indigo-600">
                                            <input type="radio" name="answers[{{ $question->id }}]" id="option_{{ $option->id }}" value="{{ $option->id }}"
                                                   class="w-5 h-5 text-indigo-600 bg-slate-100 border-slate-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600"
                                                   {{ old('answers.'.$question->id) == $option->id ? 'checked' : '' }}
                                                   required>
                                            <span class="ms-3 text-sm font-medium text-slate-900 dark:text-slate-300">{{ $option->text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">Pilihan jawaban tidak tersedia.</p>
                            @endif
                        </fieldset>
                    @endforeach

                    <div class="mt-10 text-center">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-lg shadow-md text-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                            Selesai & Lihat Hasil
                        </button>
                    </div>
                </form>
            @else
                <p class="text-center text-slate-600 dark:text-slate-400 py-10">
                    Maaf, tes bakat minat belum tersedia atau tidak ada pertanyaan yang bisa ditampilkan.
                </p>
            @endif
        </div>
    </div>
    @endsection
    
@extends('layouts.pelamar') {{-- Atau layout publik yang sesuai --}}
@section('title', 'Hasil Tes Bakat Minat Anda')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 shadow-xl rounded-lg p-6 sm:p-8 md:p-10">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-6 text-center">Hasil Tes Bakat Minat Kamu</h1>

        {{-- Menampilkan Hasil Interpretasi (dari AI atau fallback) --}}
        @if( (isset($htmlResultSummary) && !empty($htmlResultSummary)) || (isset($resultSummary) && !empty($resultSummary)) )
            <div class="prose prose-lg prose-slate dark:prose-invert max-w-none mb-8 text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-700/30 p-6 rounded-md">
                @if(isset($htmlResultSummary))
                    {!! $htmlResultSummary !!}  {{-- <--- UBAH MENJADI INI (jika $htmlResultSummary berisi HTML dari Parsedown) --}}
                @elseif(isset($resultSummary)) {{-- Fallback jika $resultSummary yang berisi HTML mentah dari AI (kurang ideal) atau teks biasa --}}
                    {!! $resultSummary !!} {{-- Atau jika $resultSummary adalah teks biasa dan ingin nl2br: {!! nl2br(e($resultSummary)) !!} --}}
                                        {{-- Tapi jika $resultSummary itu hasil dari AI yg berupa markdown/HTML, gunakan {!! $resultSummary !!} --}}
                @endif
            </div>

            {{-- Menampilkan Rincian Skor Minat Dasar (jika ada) --}}
            @if(isset($interestScores) && !empty($interestScores) && is_array($interestScores))
            <div class="mb-8 p-6 border border-indigo-200 dark:border-indigo-800 rounded-md bg-indigo-50/50 dark:bg-indigo-900/20">
                <h3 class="text-xl font-semibold text-indigo-700 dark:text-indigo-300 mb-4">Rincian Skor Minat Dasar Anda:</h3>
                <ul class="space-y-2">
                    @foreach($interestScores as $type => $score)
                        <li class="flex justify-between items-center">
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ ucfirst(str_replace('_', ' ', $type)) }}:</span>
                            <span class="px-3 py-1 text-sm font-semibold text-indigo-800 dark:text-indigo-200 bg-indigo-200 dark:bg-indigo-700 rounded-full">{{ $score }} poin</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        @else
            <p class="text-center text-slate-600 dark:text-slate-400 py-10">
                Tidak ada hasil tes yang bisa ditampilkan. Mungkin Anda belum menyelesaikan tes atau terjadi kesalahan.
            </p>
        @endif

        <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ route('quiz.start') }}" class="w-full sm:w-auto inline-block text-center px-6 py-3 border border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-500 font-semibold rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition duration-150 ease-in-out">
                Ulangi Tes
            </a>
            <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                Cari Lowongan Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Untuk menyimpan hasil sementara
use GuzzleHttp\Client;                 // <--- TAMBAHKAN IMPORT GUZZLE
use Illuminate\Support\Facades\Log;      // <--- TAMBAHKAN IMPORT LOG (untuk debugging error AI)
use Parsedown;                         // <--- TAMBAHKAN IMPORT PARSEDOWN (opsional, untuk render Markdown)

class QuizController extends Controller
{
    /**
     * Menampilkan halaman quiz dengan semua pertanyaan.
     */
    public function startQuiz()
    {
        // Ambil semua pertanyaan beserta opsinya, urutkan berdasarkan 'order' jika ada
        $questions = Question::with('options')->orderBy('order', 'asc')->get();

        if ($questions->isEmpty()) {
            // Arahkan ke halaman lain atau tampilkan pesan jika tidak ada soal
            return redirect()->route('home')->with('error', 'Maaf, tes bakat minat belum tersedia saat ini.');
        }
        // Pastikan view 'quiz.start' sudah dibuat
        return view('quiz.start', compact('questions'));
    }

    /**
     * Memproses jawaban quiz dari pengguna.
     */
    public function submitQuiz(Request $request)
    {
        // Validasi: pastikan semua pertanyaan dijawab
        $questionIds = Question::pluck('id')->toArray();
        $rules = [];
        foreach ($questionIds as $id) {
            $rules['answers.' . $id] = 'required|exists:options,id';
        }

        $request->validate($rules, [
            'answers.*.required' => 'Semua pertanyaan wajib dijawab. Silakan lengkapi jawaban Anda.',
            'answers.*.exists' => 'Pilihan jawaban tidak valid.',
        ]);

        $submittedAnswers = $request->input('answers');

        // Hitung skor berdasarkan tipe minat
        $interestScores = [];
        foreach ($submittedAnswers as $questionId => $optionId) {
            $option = Option::find($optionId);
            if ($option && $option->question_id == $questionId) {
                $type = $option->interest_type;
                if (!isset($interestScores[$type])) {
                    $interestScores[$type] = 0;
                }
                $interestScores[$type]++;
            }
        }

        arsort($interestScores);

        // --- MULAI BAGIAN MODIFIKASI UNTUK INTEGRASI AI ---

        $resultSummary = ""; // Akan diisi oleh AI atau fallback
        $htmlResultSummary = null; // Untuk hasil Markdown yang di-parse
        $dominantInterestNames = [];

        if (empty($interestScores)) {
            $resultSummary = "Tidak dapat menentukan minat Anda karena tidak ada jawaban yang valid atau tes belum lengkap.";
        } else {
            $dominantInterestsRaw = array_slice($interestScores, 0, 3, true); // Ambil 3 teratas
            foreach ($dominantInterestsRaw as $type => $score) {
                $dominantInterestNames[] = ucfirst(str_replace('_', ' ', $type));
            }

            // Siapkan data untuk prompt AI
            $promptDataScores = [];
            foreach($interestScores as $type => $score) {
                $promptDataScores[ucfirst(str_replace('_', ' ', $type))] = $score;
            }

            // Buat prompt yang akan dikirim ke Groq API
            $prompt = "Anda adalah seorang konselor karir AI yang sangat berpengalaman dan suportif, berbicara kepada seorang pencari kerja di portal Dira Jobs.\n";
            $prompt .= "Berikut adalah hasil tes bakat minat pengguna:\n";
            foreach ($promptDataScores as $type => $score) {
                $prompt .= "- Minat " . $type . ": " . $score . " poin\n";
            }
            if(!empty($dominantInterestNames)) {
                $prompt .= "\nMinat yang paling menonjol adalah: " . implode(', ', $dominantInterestNames) . ".\n\n";
            } else {
                $prompt .= "\nTidak ada minat yang menonjol secara signifikan.\n\n";
            }

            $prompt .= "Berikan analisis dan rekomendasi dengan struktur berikut:\n";
            $prompt .= "1.  **Interpretasi Kepribadian & Minat:** Jelaskan secara singkat (2-3 kalimat) kekuatan utama dan potensi pengguna berdasarkan minat dominan mereka. Jika tidak ada minat dominan, berikan panduan umum. Gunakan gaya bahasa yang positif dan memotivasi.\n";
            $prompt .= "2.  **Rekomendasi Jenis Pekerjaan:** Sarankan 2-4 jenis pekerjaan atau bidang karir spesifik yang mungkin cocok dan relevan dengan profil minat pengguna. Untuk setiap jenis pekerjaan, berikan penjelasan singkat (1-2 kalimat) mengapa itu cocok.\n";
            $prompt .= "3.  **Saran Pengembangan Diri:** Berikan 1-2 saran praktis bagi pengguna untuk mengembangkan diri agar lebih siap memasuki bidang pekerjaan yang direkomendasikan atau sesuai minatnya.\n\n";
            $prompt .= "Gunakan bahasa Indonesia yang profesional, ramah, dan mudah dipahami. Sapa pengguna dengan 'Kamu'. Pastikan jawabanmu terstruktur dengan baik menggunakan poin-poin dan formatting Markdown (seperti **tebal** dan daftar poin jika perlu).";

            // Panggil method untuk berinteraksi dengan Groq API
            $aiFullResponse = $this->getGroqInterpretation($prompt);

            if ($aiFullResponse && $aiFullResponse !== "Terjadi kesalahan saat menghubungi layanan interpretasi AI." && $aiFullResponse !== "Maaf, interpretasi AI tidak dapat dimuat saat ini.") {
                $parsedown = new Parsedown();
                $htmlResultSummary = $parsedown->text($aiFullResponse); // Konversi Markdown ke HTML
                $resultSummary = $aiFullResponse; // Simpan juga teks mentah dari AI
            } else {
                // Fallback jika AI gagal atau tidak ada respons
                $resultSummary = "Berdasarkan jawaban Anda:\n\n";
                if (count($dominantInterestNames) > 0) {
                    $resultSummary .= "Kecenderungan minat terbesar Anda ada di bidang: " . implode(', ', $dominantInterestNames) . ".\n\n";
                    $resultSummary .= "Ini menunjukkan Anda mungkin memiliki potensi atau kenyamanan dalam aktivitas yang berkaitan dengan ". strtolower(implode(', ', $dominantInterestNames)) .". ";
                    $resultSummary .= "Pertimbangkan untuk menjelajahi karir atau pelatihan yang mengasah aspek-aspek tersebut lebih lanjut!";
                } else {
                    $resultSummary .= "Kami belum bisa mengidentifikasi minat dominan Anda. Coba ikuti tes lagi dengan saksama.";
                }
                // Jika ingin, $htmlResultSummary bisa diisi dengan nl2br(e($resultSummary)) untuk fallback
                $htmlResultSummary = nl2br(e($resultSummary));
            }
        }
        // --- AKHIR BAGIAN MODIFIKASI UNTUK INTEGRASI AI ---


        // (Opsional) Simpan hasil jika user login
        $attemptId = null;
        if (Auth::check()) {
            $attempt = QuizAttempt::create([
                'user_id' => Auth::id(),
                'answers_data' => $submittedAnswers,
                'result_summary' => $resultSummary, // Simpan teks mentah atau HTML, sesuaikan
                'interest_scores' => $interestScores,
                'ai_interpretation' => $aiFullResponse, // Simpan respons AI mentah jika perlu
                'completed_at' => now(),
            ]);
            $attemptId = $attempt->id;
        }

        // Simpan hasil di session untuk ditampilkan di halaman hasil
        Session::flash('quiz_result', [
            // Kirim versi HTML jika ada, atau teks mentah jika tidak
            'resultSummary' => $htmlResultSummary ?: nl2br(e($resultSummary)),
            'interestScores' => $interestScores,
            'attemptId' => $attemptId,
        ]);

        return redirect()->route('quiz.result');
    }

    /**
     * Method baru untuk interaksi dengan Groq API.
     *
     * @param string $promptText
     * @return string|null
     */
    protected function getGroqInterpretation(string $promptText): ?string // Tambahkan tipe return nullable string
    {
        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            Log::error('GROQ_API_KEY not set for Quiz Interpretation.');
            return "Konfigurasi API Key Groq belum lengkap."; // Beri pesan error yang lebih jelas
        }

        $client = new Client();
        $apiEndpoint = 'https://api.groq.com/openai/v1/chat/completions';
        $model = env('GROQ_MODEL', 'llama3-8b-8192'); // Ambil model dari .env atau default

        try {
            $response = $client->post($apiEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messages' => [
                        // System message bisa dimasukkan langsung di prompt utama pengguna jika lebih mudah
                        ['role' => 'user', 'content' => $promptText]
                    ],
                    'model' => $model,
                    'temperature' => 0.6, // Sedikit lebih rendah untuk konsistensi
                    'max_tokens' => 700,  // Sesuaikan sesuai kebutuhan panjang respons
                ],
                'timeout' => 30, // Tambahkan timeout untuk request
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (isset($body['choices'][0]['message']['content'])) {
                return trim($body['choices'][0]['message']['content']);
            } elseif (isset($body['error'])) {
                Log::error('Groq API (Quiz) - API Error: ', $body['error']);
                return "Layanan AI mengembalikan error: " . ($body['error']['message'] ?? 'Error tidak diketahui');
            } else {
                Log::error('Groq API (Quiz) - Unexpected response structure: ', $body);
                return "Maaf, format balasan dari AI tidak sesuai.";
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Groq API (Quiz) Request Exception: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('Groq API (Quiz) Response Error Body: ' . $e->getResponse()->getBody()->getContents());
            }
            return "Terjadi kesalahan saat menghubungi layanan interpretasi AI (Request Exception).";
        } catch (\Exception $e) {
            Log::error('General Error in getGroqInterpretation: ' . $e->getMessage());
            return "Terjadi kesalahan umum pada server saat memproses interpretasi AI.";
        }
    }


    /**
     * Menampilkan halaman hasil quiz.
     */
    public function showResult()
    {
        $quizResult = Session::get('quiz_result');

        if (!$quizResult) {
            return redirect()->route('quiz.start')->with('error', 'Anda harus menyelesaikan tes terlebih dahulu untuk melihat hasilnya.');
        }

        // Pastikan view 'quiz.result' sudah dibuat dan bisa menampilkan $quizResult['resultSummary']
        // yang mungkin berisi HTML
        return view('quiz.result', $quizResult);
    }
}
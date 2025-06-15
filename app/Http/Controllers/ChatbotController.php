<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Untuk membuat HTTP request ke API
use Illuminate\Support\Facades\Log;   // Untuk mencatat error
use Exception;                       // Untuk menangani error umum

class ChatbotController extends Controller
{
    /**
     * Menampilkan halaman chatbot.
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Memproses pesan dari user dan memberikan respons dari Groq API.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $apiKey = env('GROQ_API_KEY'); // Mengambil API Key dari .env

        // Validasi jika API Key belum diatur
        if (!$apiKey) {
            Log::error('GROQ_API_KEY is not set in .env file.');
            return response()->json(['response' => 'Maaf, chatbot sedang tidak tersedia karena kunci API tidak diatur.'], 500);
        }

        try {
            // Melakukan POST request ke Groq API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey, // Autentikasi dengan API Key
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [ // Endpoint API Chat Completions
                'model' => 'llama3-8b-8192', // Model AI yang digunakan. Anda bisa coba 'mixtral-8x7b-32768' atau yang lain yang tersedia di Groq.
                'messages' => [
                    // Pesan 'system' untuk memberikan konteks atau instruksi pada AI
                    ['role' => 'system', 'content' => 'Anda adalah ahli rekomendasi tanaman yang ramah dan membantu. Berikan rekomendasi dan informasi tentang tanaman, lahan, dan perawatan tanaman. Jawablah dengan singkat dan jelas. Jangan memberikan saran medis atau keuangan.'],
                    // Pesan dari user
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7, // Mengontrol tingkat kreativitas/randomness respons (0.0 = paling konsisten, 1.0 = paling kreatif)
                'max_tokens' => 200, // Batas panjang respons dari AI (dalam token/kata)
            ]);

            // Memeriksa apakah permintaan API berhasil
            if ($response->successful()) {
                $responseData = $response->json(); // Mengambil respons dalam bentuk JSON
                // Mengambil konten pesan dari respons AI
                $botResponse = $responseData['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat menghasilkan respons saat ini.';
            } else {
                // Mencatat error jika API tidak berhasil (misalnya, status code 4xx atau 5xx)
                Log::error('Groq API Error: ' . $response->status() . ' - ' . $response->body());
                $botResponse = 'Maaf, ada masalah saat menghubungi server AI. Silakan coba lagi nanti.';
            }
        } catch (Exception $e) {
            // Menangkap error jika ada masalah koneksi atau error tak terduga lainnya
            Log::error('Chatbot sendMessage Exception: ' . $e->getMessage());
            $botResponse = 'Terjadi kesalahan internal. Silakan coba lagi.';
        }

        return response()->json(['response' => $botResponse]);
    }
}
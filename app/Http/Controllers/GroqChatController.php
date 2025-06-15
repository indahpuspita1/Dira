<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Log;

    class GroqChatController extends Controller
    {
        public function sendMessage(Request $request)
        {
            $request->validate([
                'message' => 'required|string|max:1000', // Batasi panjang pesan
                'history' => 'nullable|array' // Opsional: untuk mengirim histori percakapan
            ]);

            $userMessage = $request->input('message');
            $conversationHistory = $request->input('history', []); // Ambil histori atau array kosong

            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                return response()->json(['reply' => 'Konfigurasi API Key Groq belum lengkap.'], 500);
            }

            // Siapkan pesan untuk Groq API, termasuk histori jika ada
            $messages = [];

            // (Opsional) Tambahkan pesan sistem untuk memberikan konteks pada AI
            $messages[] = [
                'role' => 'system',
                'content' => 'Kamu adalah DiraBot, asisten AI yang ramah dan membantu di portal lowongan kerja Dira. Berikan jawaban yang singkat, jelas, dan relevan terkait pencarian kerja, tips karir, atau cara menggunakan website Dira. Jika tidak tahu, katakan tidak tahu dengan sopan.'
            ];

            // Tambahkan histori percakapan sebelumnya
            foreach ($conversationHistory as $chat) {
                if (isset($chat['role']) && isset($chat['content'])) {
                     $messages[] = ['role' => $chat['role'], 'content' => $chat['content']];
                }
            }

            // Tambahkan pesan terbaru dari user
            $messages[] = ['role' => 'user', 'content' => $userMessage];


            $client = new Client();
            $apiEndpoint = 'https://api.groq.com/openai/v1/chat/completions';
            // Pilih model yang tersedia di Groq, contoh: mixtral-8x7b-32768, llama3-8b-8192, gemma-7b-it
            $model = 'llama3-8b-8192'; // Ganti dengan model pilihanmu

            try {
                $response = $client->post($apiEndpoint, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'messages' => $messages,
                        'model' => $model,
                        // 'temperature' => 0.7, // Opsional: Atur kreativitas (0.0 - 1.0)
                        // 'max_tokens' => 150,  // Opsional: Batasi panjang balasan
                    ],
                ]);

                $body = json_decode($response->getBody()->getContents(), true);

                if (isset($body['choices'][0]['message']['content'])) {
                    $botReply = $body['choices'][0]['message']['content'];
                } else {
                    Log::error('Groq API - Unexpected response structure: ', $body);
                    $botReply = 'Maaf, saya tidak bisa memberikan balasan saat ini.';
                }

                return response()->json(['reply' => $botReply]);

            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error('Groq API Request Error: ' . $e->getMessage());
                if ($e->hasResponse()) {
                    Log::error('Groq API Response Error Body: ' . $e->getResponse()->getBody()->getContents());
                }
                return response()->json(['reply' => 'Maaf, layanan chatbot sedang mengalami gangguan teknis.'], 503);
            } catch (\Exception $e) {
                Log::error('General Error in GroqChatController: ' . $e->getMessage());
                return response()->json(['reply' => 'Maaf, terjadi kesalahan internal pada server kami.'], 500);
            }
        }
    }
    
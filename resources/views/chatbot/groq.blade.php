    @extends('layouts.pelamar') {{-- Atau layout publik/pelamar yang sesuai, misal layouts.frontend --}}
    @section('title', 'Konsultasi dengan Dira AI (Groq)')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-xl mx-auto bg-white dark:bg-slate-800 shadow-xl rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="p-4 border-b dark:border-slate-700 flex items-center space-x-3">
                {{-- Icon Sederhana --}}
                <svg class="w-8 h-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.154 48.154 0 0 0 5.723 0 1.14 1.14 0 0 1 .778.332L19.5 21v-2.895c1.108-.086 2.206-.209 3.293-.37a3.746 3.746 0 0 0 2.707-3.227V6.75A3.75 3.75 0 0 0 21.75 3H2.25A3.75 3.75 0 0 0-1.5 6.75v6.26Z" />
                </svg>
                <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200">Chat dengan Dira AI (Groq)</h2>
            </div>
            <div id="chatbox" class="p-4 h-96 overflow-y-auto space-y-4 bg-slate-50 dark:bg-slate-900/50">
                {{-- Pesan Awal dari Bot --}}
                <div class="flex">
                    <div class="bg-indigo-500 text-white p-3 rounded-lg max-w-xs shadow">
                        Halo! Saya DiraBot. Ada yang bisa saya bantu seputar karir dan lowongan di Dira?
                    </div>
                </div>
            </div>
            <div class="p-4 border-t dark:border-slate-700">
                <form id="chat-form-groq" class="flex gap-2">
                    <input type="text" id="message-input-groq" placeholder="Ketik pertanyaanmu di sini..."
                           class="flex-grow px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-700 dark:text-slate-200" required>
                    <button type="submit" id="send-button-groq"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-150 ease-in-out">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatForm = document.getElementById('chat-form-groq');
            const messageInput = document.getElementById('message-input-groq');
            const chatbox = document.getElementById('chatbox');
            const sendButton = document.getElementById('send-button-groq');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let conversationHistory = []; // Untuk menyimpan histori percakapan

            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const userMessage = messageInput.value.trim();
                if (!userMessage) return;

                appendMessage(userMessage, 'user');
                conversationHistory.push({ role: 'user', content: userMessage }); // Tambahkan ke histori
                messageInput.value = '';
                messageInput.disabled = true;
                sendButton.disabled = true;
                sendButton.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'; // Indikator loading di tombol

                fetch("{{ route('chat.groq.send') }}", { // Pastikan route POST ini sudah ada
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        history: conversationHistory.slice(-6)
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    const botReply = data.reply || 'Tidak ada balasan.';
                    appendMessage(botReply, 'bot');
                    conversationHistory.push({ role: 'assistant', content: botReply });
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Maaf, terjadi kesalahan. Coba lagi nanti.';
                    if (error && error.reply) {
                        errorMessage = error.reply;
                    } else if (error && error.message) {
                        errorMessage = error.message;
                    }
                    appendMessage(errorMessage, 'bot-error');
                })
                .finally(() => {
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    sendButton.innerHTML = 'Kirim';
                    messageInput.focus();
                });
            });

            function appendMessage(message, sender) {
                const messageWrapper = document.createElement('div');
                messageWrapper.classList.add('flex', 'mb-3', 'clear-both');

                const bubbleDiv = document.createElement('div');
                bubbleDiv.classList.add('p-3', 'rounded-lg', 'max-w-[80%]', 'shadow', 'text-sm', 'leading-relaxed');
                bubbleDiv.textContent = message;

                if (sender === 'user') {
                    messageWrapper.classList.add('justify-end');
                    bubbleDiv.classList.add('bg-indigo-500', 'dark:bg-indigo-600', 'text-white', 'ml-auto');
                } else if (sender === 'bot-error') {
                    messageWrapper.classList.add('justify-start');
                    bubbleDiv.classList.add('bg-red-100', 'dark:bg-red-700', 'text-red-700', 'dark:text-red-200', 'mr-auto');
                }
                else { // bot
                    messageWrapper.classList.add('justify-start');
                    bubbleDiv.classList.add('bg-slate-200', 'dark:bg-slate-600', 'text-slate-800', 'dark:text-slate-200', 'mr-auto');
                }
                messageWrapper.appendChild(bubbleDiv);
                chatbox.appendChild(messageWrapper);
                chatbox.scrollTop = chatbox.scrollHeight;
            }
        });
    </script>
    @endsection
    
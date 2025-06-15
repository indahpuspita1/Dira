@extends('layouts.pelamar')
@section('title', 'Konsultasi dengan Dira AI (Groq)')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chatbot Tanaman') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-teal-50 to-cyan-100"> {{-- Ubah warna gradasi --}}
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 border-gray-200 text-center">Ngobrol dengan Bot Kami!</h3>

                    <div id="chat-box" class="border border-gray-300 p-4 h-96 overflow-y-auto mb-4 rounded-lg bg-gray-50 flex flex-col space-y-4 shadow-inner custom-scrollbar"> {{-- Tambah shadow-inner dan custom-scrollbar --}}
                        {{-- Pesan akan muncul di sini --}}
                        <div class="flex items-start">
                            <div class="bg-blue-200 text-blue-800 p-3 rounded-t-xl rounded-br-xl max-w-[80%] shadow-md"> {{-- Gaya bubble bot --}}
                                <p>Halo! Saya chatbot Anda. Saya bisa membantu dengan pertanyaan umum tentang tanaman atau lahan.</p>
                            </div>
                        </div>
                    </div>

                    <form id="chat-form" class="flex gap-2"> {{-- Tambah gap antar elemen --}}
                        @csrf
                        <input type="text" id="user-message" name="message" placeholder="Ketik pesan Anda..."
                               class="flex-grow rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3" required> {{-- Ubah rounded dan padding --}}
                        <button type="submit" class="px-6 py-3 bg-indigo-700 text-white rounded-lg shadow-md hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kirim
                        </button>
                    </form>

                    <p id="error-message" class="text-red-600 text-sm mt-3 hidden text-center font-medium"></p> {{-- Styling pesan error --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to add messages to chatbox
            function addMessage(sender, message) {
                let chatBox = $('#chat-box');
                let messageHtml;
                if (sender === 'user') {
                    messageHtml = '<div class="flex justify-end items-start"><div class="bg-indigo-500 text-white p-3 rounded-t-xl rounded-bl-xl max-w-[80%] shadow-md">' + message + '</div></div>'; // Gaya bubble user
                } else { // bot
                    messageHtml = '<div class="flex items-start"><div class="bg-blue-200 text-blue-800 p-3 rounded-t-xl rounded-br-xl max-w-[80%] shadow-md">' + message + '</div></div>'; // Gaya bubble bot
                }
                chatBox.append(messageHtml);
                chatBox.scrollTop(chatBox[0].scrollHeight); // Scroll ke bawah
            }

            $('#chat-form').on('submit', function(e) {
                e.preventDefault();

                let userMessage = $('#user-message').val();
                let errorMessage = $('#error-message');

                if (userMessage.trim() === '') {
                    errorMessage.removeClass('hidden').text('Pesan tidak boleh kosong.');
                    return;
                }

                addMessage('user', userMessage); // Tampilkan pesan user
                errorMessage.addClass('hidden').text(''); // Bersihkan pesan error

                $.ajax({
                    url: "{{ route('chatbot.sendMessage') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        message: userMessage
                    },
                    success: function(response) {
                        addMessage('bot', response.response); // Tampilkan respons bot
                        $('#user-message').val(''); // Kosongkan input
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", xhr.responseText);
                        errorMessage.removeClass('hidden').text('Gagal mengirim pesan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
    @endpush

    {{-- Tambahkan CSS kustom untuk scrollbar (Opsional, jika ingin custom) --}}
    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    @endpush
</x-app-layout>
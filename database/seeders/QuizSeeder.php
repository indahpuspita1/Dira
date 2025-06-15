<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan cek foreign key sementara untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Option::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tipe Minat yang akan digunakan:
        // 'Kreatif', 'Analitis', 'Sosial', 'Organisatoris', 'Teknis'

        // Pertanyaan 1
        $q1 = Question::create(['text' => 'Ketika saya mengerjakan sebuah tugas, saya paling menikmati bagian...', 'order' => 1]);
        $q1->options()->createMany([
            ['text' => 'Mencari cara baru dan unik untuk menyelesaikannya.', 'interest_type' => 'Kreatif'],
            ['text' => 'Menganalisis semua detail dan memastikan tidak ada kesalahan.', 'interest_type' => 'Analitis'],
            ['text' => 'Bekerja sama dengan orang lain untuk mencapai tujuan.', 'interest_type' => 'Sosial'],
        ]);

        // Pertanyaan 2
        $q2 = Question::create(['text' => 'Aktivitas yang paling membuat saya bersemangat adalah...', 'order' => 2]);
        $q2->options()->createMany([
            ['text' => 'Membuat jadwal dan daftar tugas yang terperinci.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Membongkar dan merakit kembali sebuah perangkat.', 'interest_type' => 'Teknis'],
            ['text' => 'Menghasilkan karya seni atau tulisan.', 'interest_type' => 'Kreatif'],
        ]);

        // Pertanyaan 3
        $q3 = Question::create(['text' => 'Jika ada masalah dalam tim, saya biasanya...', 'order' => 3]);
        $q3->options()->createMany([
            ['text' => 'Mencoba menengahi dan mencari solusi yang bisa diterima semua pihak.', 'interest_type' => 'Sosial'],
            ['text' => 'Mencari akar penyebab masalahnya secara logis.', 'interest_type' => 'Analitis'],
            ['text' => 'Mengambil alih dan mengarahkan tim untuk segera bertindak.', 'interest_type' => 'Organisatoris'],
        ]);

        // Pertanyaan 4
        $q4 = Question::create(['text' => 'Saya lebih memilih pekerjaan yang memungkinkan saya untuk...', 'order' => 4]);
        $q4->options()->createMany([
            ['text' => 'Menggunakan imajinasi dan menciptakan hal baru.', 'interest_type' => 'Kreatif'],
            ['text' => 'Bekerja dengan angka dan data secara akurat.', 'interest_type' => 'Analitis'],
            ['text' => 'Berinteraksi dan membantu banyak orang.', 'interest_type' => 'Sosial'],
        ]);

        // Pertanyaan 5
        $q5 = Question::create(['text' => 'Lingkungan kerja yang ideal bagi saya adalah yang...', 'order' => 5]);
        $q5->options()->createMany([
            ['text' => 'Terstruktur dengan aturan dan prosedur yang jelas.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Memberi kebebasan untuk bereksperimen dan mencoba ide baru.', 'interest_type' => 'Kreatif'],
            ['text' => 'Memungkinkan saya menggunakan keterampilan tangan atau alat secara langsung.', 'interest_type' => 'Teknis'],
        ]);

        // --- PENAMBAHAN 10 SOAL BARU ---

        // Pertanyaan 6
        $q6 = Question::create(['text' => 'Ketika belajar hal baru, saya lebih suka...', 'order' => 6]);
        $q6->options()->createMany([
            ['text' => 'Membaca teori dan konsep dasarnya terlebih dahulu.', 'interest_type' => 'Analitis'],
            ['text' => 'Langsung mencoba dan mempraktikkannya.', 'interest_type' => 'Teknis'],
            ['text' => 'Berdiskusi dengan teman atau mentor.', 'interest_type' => 'Sosial'],
        ]);

        // Pertanyaan 7
        $q7 = Question::create(['text' => 'Saya merasa paling produktif ketika...', 'order' => 7]);
        $q7->options()->createMany([
            ['text' => 'Memiliki rencana kerja yang jelas dan terorganisir.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Diberi kebebasan untuk mengatur cara kerja saya sendiri.', 'interest_type' => 'Kreatif'],
            ['text' => 'Bekerja dalam suasana yang kolaboratif dan suportif.', 'interest_type' => 'Sosial'],
        ]);

        // Pertanyaan 8
        $q8 = Question::create(['text' => 'Dalam mengambil keputusan, saya lebih mengandalkan...', 'order' => 8]);
        $q8->options()->createMany([
            ['text' => 'Logika dan fakta yang ada.', 'interest_type' => 'Analitis'],
            ['text' => 'Intuisi dan perasaan saya.', 'interest_type' => 'Kreatif'], // Bisa juga ke arah sosial jika terkait empati
            ['text' => 'Pengalaman praktis yang pernah saya alami.', 'interest_type' => 'Teknis'],
        ]);

        // Pertanyaan 9
        $q9 = Question::create(['text' => 'Saya tertarik pada pekerjaan yang melibatkan...', 'order' => 9]);
        $q9->options()->createMany([
            ['text' => 'Pelayanan kepada masyarakat atau individu.', 'interest_type' => 'Sosial'],
            ['text' => 'Pengelolaan proyek dan sumber daya.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Pemecahan masalah teknis yang kompleks.', 'interest_type' => 'Teknis'],
        ]);

        // Pertanyaan 10
        $q10 = Question::create(['text' => 'Jika diberi pilihan, saya lebih suka menghabiskan waktu luang dengan...', 'order' => 10]);
        $q10->options()->createMany([
            ['text' => 'Membaca buku atau artikel ilmiah.', 'interest_type' => 'Analitis'],
            ['text' => 'Berkumpul dengan teman-teman atau keluarga.', 'interest_type' => 'Sosial'],
            ['text' => 'Melakukan hobi yang berkaitan dengan kerajinan tangan atau mekanik.', 'interest_type' => 'Teknis'],
        ]);

        // Pertanyaan 11
        $q11 = Question::create(['text' => 'Ketika menghadapi tantangan, reaksi pertama saya adalah...', 'order' => 11]);
        $q11->options()->createMany([
            ['text' => 'Membuat daftar langkah-langkah untuk mengatasinya.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Mencari informasi sebanyak mungkin tentang tantangan tersebut.', 'interest_type' => 'Analitis'],
            ['text' => 'Mencoba pendekatan yang tidak biasa atau out-of-the-box.', 'interest_type' => 'Kreatif'],
        ]);

        // Pertanyaan 12
        $q12 = Question::create(['text' => 'Saya merasa puas jika hasil kerja saya...', 'order' => 12]);
        $q12->options()->createMany([
            ['text' => 'Memberikan dampak positif bagi orang lain.', 'interest_type' => 'Sosial'],
            ['text' => 'Sesuai dengan standar kualitas yang tinggi dan akurat.', 'interest_type' => 'Analitis'], // Bisa juga Organisatoris
            ['text' => 'Berfungsi dengan baik dan efisien secara teknis.', 'interest_type' => 'Teknis'],
        ]);

        // Pertanyaan 13
        $q13 = Question::create(['text' => 'Dalam sebuah presentasi, saya lebih fokus pada...', 'order' => 13]);
        $q13->options()->createMany([
            ['text' => 'Penyampaian visual yang menarik dan inovatif.', 'interest_type' => 'Kreatif'],
            ['text' => 'Keakuratan data dan argumen yang logis.', 'interest_type' => 'Analitis'],
            ['text' => 'Interaksi dengan audiens dan menjawab pertanyaan mereka.', 'interest_type' => 'Sosial'],
        ]);

        // Pertanyaan 14
        $q14 = Question::create(['text' => 'Saya lebih termotivasi oleh...', 'order' => 14]);
        $q14->options()->createMany([
            ['text' => 'Kesempatan untuk memimpin dan mengatur sebuah tim atau proyek.', 'interest_type' => 'Organisatoris'],
            ['text' => 'Kebebasan untuk mengekspresikan ide-ide orisinal saya.', 'interest_type' => 'Kreatif'],
            ['text' => 'Kesempatan untuk memecahkan masalah yang rumit dan menantang.', 'interest_type' => 'Analitis'],
        ]);

        // Pertanyaan 15
        $q15 = Question::create(['text' => 'Pekerjaan impian saya adalah pekerjaan yang...', 'order' => 15]);
        $q15->options()->createMany([
            ['text' => 'Memungkinkan saya membangun atau menciptakan sesuatu dengan tangan saya.', 'interest_type' => 'Teknis'],
            ['text' => 'Memberikan kontribusi nyata bagi kesejahteraan komunitas.', 'interest_type' => 'Sosial'],
            ['text' => 'Membutuhkan perencanaan strategis dan pengelolaan yang baik.', 'interest_type' => 'Organisatoris'],
        ]);

        $this->command->info('Tabel Questions dan Options berhasil diisi dengan 15 soal.');
    }
}

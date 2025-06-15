<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Bisa untuk tamu
        $table->json('answers_data')->nullable(); // Menyimpan jawaban user (misal: [question_id => option_id])
        $table->text('result_summary')->nullable(); // Kesimpulan bakat/minat
        $table->json('interest_scores')->nullable(); // Skor per tipe minat
        $table->timestamp('completed_at');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};

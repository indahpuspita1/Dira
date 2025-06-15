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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pelamar yang melamar
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');   // Lowongan yang dilamar
            $table->string('status')->default('pending'); // Misal: pending, reviewed, accepted, rejected
            $table->string('resume')->nullable();         // Path ke file resume jika ada fitur upload
            $table->text('cover_letter')->nullable();     // Surat lamaran jika ada
            $table->timestamp('applied_at')->nullable();  // Waktu ketika lamaran dikirim
            $table->timestamps(); // created_at dan updated_at

            // Opsional: Mencegah satu user melamar lowongan yang sama berkali-kali
            $table->unique(['user_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
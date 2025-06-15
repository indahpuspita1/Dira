<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('workshop_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pelamar yang mendaftar
            $table->foreignId('workshop_id')->constrained('workshops')->onDelete('cascade');
            $table->string('name'); // Nama pendaftar (bisa diambil dari user, atau diisi ulang)
            $table->string('email'); // Email pendaftar
            $table->string('phone')->nullable(); // Nomor telepon (opsional)
            $table->timestamp('registration_date');
            $table->string('status')->default('confirmed'); // Misal: confirmed, pending_payment, cancelled. Untuk sekarang default 'confirmed'
            $table->string('unique_registration_code')->unique()->nullable(); // Kode unik untuk kartu
            $table->timestamps();
            $table->unique(['user_id', 'workshop_id']); // Satu user hanya bisa daftar 1x per workshop
        });
    }
    public function down(): void {
        Schema::dropIfExists('workshop_registrations');
    }
};
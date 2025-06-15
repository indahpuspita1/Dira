<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_jobs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->string('title');
            $table->string('company');
            $table->text('description');
            $table->string('location');
            $table->date('deadline');
            $table->string('image')->nullable(); // Bisa null dulu, atau langsung wajib
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
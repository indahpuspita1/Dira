<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // User (admin) yang membuat
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable(); // Path gambar, bisa null jika tidak wajib
            $table->dateTime('date_time');    // Tanggal dan Waktu Workshop
            $table->string('location_or_link'); // Lokasi fisik atau link online
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
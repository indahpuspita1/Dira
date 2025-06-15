<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_disability_category', function (Blueprint $table) {
            $table->foreignId('job_id')
                  ->constrained('jobs')
                  ->onDelete('cascade');
            $table->foreignId('disability_category_id')
                  ->constrained('disability_categories')
                  ->onDelete('cascade');
            $table->primary(['job_id', 'disability_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_disability_category');
    }
};
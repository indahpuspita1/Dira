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
        Schema::table('applications', function (Blueprint $table) {
            // Hapus kolom resume dan cover_letter jika ingin diganti dengan biodata yang lebih lengkap di sini
            // atau biarkan jika masih ingin ada resume terpisah. Untuk contoh ini, kita asumsikan
            // biodata ini adalah pengganti atau pelengkap utama.

            // $table->dropColumn(['resume', 'cover_letter']); // Hapus jika tidak dipakai lagi

            // Tambahkan kolom baru untuk biodata
            // Nama dan email bisa diambil dari Auth::user() tapi kita simpan juga di sini
            // untuk snapshot data saat melamar, atau jika pelamar ingin menggunakan data berbeda.
            $table->string('applicant_name')->after('job_id');
            $table->string('applicant_email')->after('applicant_name');
            $table->string('applicant_phone')->nullable()->after('applicant_email');
            $table->text('address')->nullable()->after('applicant_phone');
            $table->string('education_level')->nullable()->after('address'); // Contoh: SMA/SMK, D3, S1
            $table->text('work_experience_summary')->nullable()->after('education_level');
            $table->string('face_photo')->nullable()->after('work_experience_summary'); // Path ke foto wajah
            $table->string('application_code')->unique()->nullable()->after('status'); // Kode unik formulir pendaftaran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_name',
                'applicant_email',
                'applicant_phone',
                'address',
                'education_level',
                'work_experience_summary',
                'face_photo',
                'application_code',
            ]);
            // Jika sebelumnya menghapus resume & cover_letter, tambahkan kembali di sini jika perlu rollback
            // $table->string('resume')->nullable();
            // $table->text('cover_letter')->nullable();
        });
    }
};

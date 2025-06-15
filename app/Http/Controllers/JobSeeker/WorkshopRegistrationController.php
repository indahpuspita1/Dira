<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\WorkshopRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Untuk generate kode unik

class WorkshopRegistrationController extends Controller
{
    /**
     * Menampilkan form pendaftaran workshop.
     */
    public function create(Workshop $workshop)
    {
        // Cek apakah user sudah terdaftar di workshop ini
        $user = Auth::user();
        $isRegistered = WorkshopRegistration::where('user_id', $user->id)
                                            ->where('workshop_id', $workshop->id)
                                            ->exists();
        if ($isRegistered) {
            return redirect()->route('workshops.show', $workshop->id)
                             ->with('warning', 'Anda sudah terdaftar pada workshop ini.');
        }

        // Cek apakah workshop masih bisa didaftar (misal belum lewat tanggalnya)
        if ($workshop->date_time < now()) {
             return redirect()->route('workshops.show', $workshop->id)
                             ->with('error', 'Pendaftaran untuk workshop ini sudah ditutup.');
        }

        return view('job_seeker.workshops.register', compact('workshop', 'user'));
    }

    /**
     * Menyimpan data pendaftaran workshop.
     */
    public function store(Request $request, Workshop $workshop)
    {
        $user = Auth::user();

        // Cek lagi apakah sudah terdaftar (double check)
        $isRegistered = WorkshopRegistration::where('user_id', $user->id)
                                            ->where('workshop_id', $workshop->id)
                                            ->exists();
        if ($isRegistered) {
            return redirect()->route('workshops.show', $workshop->id)
                             ->with('warning', 'Anda sudah terdaftar pada workshop ini.');
        }
        if ($workshop->date_time < now()) {
             return redirect()->route('workshops.show', $workshop->id)
                             ->with('error', 'Pendaftaran untuk workshop ini sudah ditutup.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            // Tambahkan validasi lain jika ada field tambahan di form
        ]);

        // Generate kode registrasi unik
        $uniqueCode = 'WSREG-' . strtoupper(Str::random(8));
        while (WorkshopRegistration::where('unique_registration_code', $uniqueCode)->exists()) {
            $uniqueCode = 'WSREG-' . strtoupper(Str::random(8)); // Pastikan benar-benar unik
        }

        $registration = WorkshopRegistration::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null,
            'registration_date' => now(),
            'status' => $workshop->price > 0 ? 'pending_payment' : 'confirmed', // Jika berbayar, status pending. Jika gratis, langsung confirmed.
            'unique_registration_code' => $uniqueCode,
        ]);

        // Jika gratis, langsung arahkan ke kartu. Jika berbayar, arahkan ke info pembayaran (belum diimplementasikan)
        if ($workshop->price <= 0) {
            return redirect()->route('workshops.registration.card', $registration->id)
                             ->with('success', 'Anda berhasil terdaftar pada workshop! Berikut adalah kartu workshop Anda.');
        } else {
            // TODO: Implementasi logika pembayaran
            return redirect()->route('workshops.show', $workshop->id)
                             ->with('success', 'Pendaftaran Anda diterima. Silakan lakukan pembayaran (fitur pembayaran belum tersedia).')
                             ->with('info', 'Detail pembayaran akan diinformasikan lebih lanjut.');
        }
    }

    public function showCard(WorkshopRegistration $registration) // Route Model Binding
    {
        // Pastikan user yang login adalah pemilik pendaftaran ini atau admin
        if (Auth::id() !== $registration->user_id && !(Auth::check() && Auth::user()->role === 'admin')) {
            abort(403, 'Anda tidak memiliki akses ke kartu pendaftaran ini.');
        }

        // Eager load relasi workshop dan user jika belum
        $registration->load(['user', 'workshop.admin']);

        // Pastikan view 'job_seeker.workshops.registration_card' sudah dibuat
        return view('job_seeker.workshops.registration_card', compact('registration'));
    }
}

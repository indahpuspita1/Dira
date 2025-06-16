<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WorkshopController extends Controller
{
    /**
     * Menampilkan daftar workshop.
     */
    public function index()
    {
        $workshops = Workshop::with('admin')->latest()->paginate(10);
        return view('admin.workshops.index', compact('workshops'));
    }

    /**
     * Menampilkan form untuk membuat workshop baru.
     */
    public function create()
    {
        return view('admin.workshops.create');
    }

    /**
     * Menyimpan workshop baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_time' => 'required|date', // Bisa juga 'datetime-local' jika inputnya begitu
            'location_or_link' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'price' => 'required|numeric|min:0',
        ], [
            'title.required' => 'Judul workshop wajib diisi.',
            'description.required' => 'Deskripsi workshop wajib diisi.',
            'date_time.required' => 'Tanggal & waktu workshop wajib diisi.',
            'date_time.date' => 'Format tanggal & waktu tidak valid.',
            'location_or_link.required' => 'Lokasi atau link workshop wajib diisi.',
            'image.required' => 'Gambar workshop wajib diupload.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('workshop_images', 'public');
        }

        Auth::user()->postedWorkshops()->create([ // Pastikan relasi postedWorkshops() ada di model User
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date_time' => $validatedData['date_time'],
            'location_or_link' => $validatedData['location_or_link'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.workshops.index')
                         ->with('success', 'Workshop berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu workshop.
     */
    public function show(Workshop $workshop)
    {
        $workshop->load('admin');
        return view('admin.workshops.show', compact('workshop'));
    }

    /**
     * Menampilkan form untuk mengedit workshop.
     */
    public function edit(Workshop $workshop)
    {
        return view('admin.workshops.edit', compact('workshop'));
    }

    /**
     * Memperbarui workshop di database.
     */
    public function update(Request $request, Workshop $workshop)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_time' => 'required|date',
            'location_or_link' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'price' => 'required|numeric|min:0',
        ]);

        $imagePath = $workshop->image;
        if ($request->hasFile('image')) {
            if ($workshop->image) {
                Storage::disk('public')->delete($workshop->image);
            }
            $imagePath = $request->file('image')->store('workshop_images', 'public');
        }

        $workshop->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date_time' => $validatedData['date_time'],
            'location_or_link' => $validatedData['location_or_link'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.workshops.index')
                         ->with('success', 'Workshop berhasil diperbarui.');
    }

    /**
     * Menghapus workshop dari database.
     */
    public function destroy(Workshop $workshop)
    {
        if ($workshop->image) {
            Storage::disk('public')->delete($workshop->image);
        }
        $workshop->delete();

        return redirect()->route('admin.workshops.index')
                         ->with('success', 'Workshop berhasil dihapus.');
    }
}
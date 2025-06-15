<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Pastikan ini ada
use App\Models\Job;
use App\Models\DisabilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar jika diperlukan
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua data lowongan dari database dengan pagination, urutkan berdasarkan yang terbaru
        // Hanya ambil lowongan yang diposting oleh admin yang sedang login (jika diperlukan,
        // tapi untuk admin biasanya bisa melihat semua atau berdasarkan role tertentu)
        // Untuk contoh ini, kita ambil semua lowongan.
        $jobs = Job::latest()->paginate(10); // Menampilkan 10 lowongan per halaman

        // Kirim data jobs ke view admin.jobs.index
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil semua data kategori disabilitas dari database
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();

        // Tampilkan view admin.jobs.create dan kirim data disabilityCategories
        return view('admin.jobs.create', compact('disabilityCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi data input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after_or_equal:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Gambar wajib, maks 2MB
            'disability_categories' => 'required|array', // Wajib ada, minimal 1
            'disability_categories.*' => 'exists:disability_categories,id' // Setiap item harus ada di tabel disability_categories
        ], [
            // Pesan error kustom (opsional)
            'title.required' => 'Judul lowongan wajib diisi.',
            'company.required' => 'Nama perusahaan wajib diisi.',
            'description.required' => 'Deskripsi lowongan wajib diisi.',
            'location.required' => 'Lokasi wajib diisi.',
            'deadline.required' => 'Tanggal deadline wajib diisi.',
            'deadline.date' => 'Format tanggal deadline tidak valid.',
            'deadline.after_or_equal' => 'Tanggal deadline tidak boleh kurang dari hari ini.',
            'image.required' => 'Gambar lowongan wajib diupload.',
            'image.image' => 'File yang diupload harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'disability_categories.required' => 'Pilih minimal satu kategori disabilitas.',
            'disability_categories.*.exists' => 'Kategori disabilitas yang dipilih tidak valid.'
        ]);

         $imageUrl = null;
        if ($request->hasFile('image')) {
            // UPLOAD KE CLOUDINARY
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'dira-jobs/job' // Folder di Cloudinary
            ])->getSecurePath();

            $imageUrl = $uploadedFileUrl;
        }

        // 3. Simpan data lowongan ke database
        // Menggunakan relasi dari User (admin) untuk langsung mengisi admin_id
        $job = Auth::user()->postedJobs()->create([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $imageUrl,
            // admin_id akan otomatis terisi oleh relasi postedJobs()
        ]);

        // Alternatif jika tidak menggunakan relasi langsung saat create:
        // $job = new Job();
        // $job->admin_id = Auth::id(); // atau Auth::user()->id
        // $job->title = $validatedData['title'];
        // ... (isi field lainnya)
        // $job->image = $imagePath;
        // $job->save();

        // 4. Simpan relasi ke kategori disabilitas (Many-to-Many)
        if ($job && !empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        }

        // 5. Redirect ke halaman index lowongan dengan pesan sukses
        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        // Load relasi kategori disabilitas agar bisa ditampilkan di view show
        $job->load('disabilityCategories', 'admin');
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        // Load relasi agar bisa diakses di form (misal untuk checkbox yang sudah tercentang)
        $job->load('disabilityCategories');
        return view('admin.jobs.edit', compact('job', 'disabilityCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after_or_equal:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar opsional saat update
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        $imagePath = $job->image; // Simpan path gambar lama
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan jika gambar baru diupload
            if ($job->image) {
                Storage::disk('public')->delete($job->image);
            }
            $imagePath = $request->file('image')->store('job_images', 'public');
        }

        $job->update([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $imagePath, // Gunakan path gambar baru atau yang lama jika tidak ada upload baru
        ]);

        if (!empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        } else {
            // Jika tidak ada kategori yang dipilih, hapus semua relasi kategori (sesuai kebutuhan)
            $job->disabilityCategories()->detach();
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        // Hapus gambar dari storage jika ada
        if ($job->image) {
            Storage::disk('public')->delete($job->image);
        }

        // Hapus relasi di tabel pivot (otomatis jika onDelete('cascade') di migrasi, tapi bisa juga manual)
        // $job->disabilityCategories()->detach(); // Tidak perlu jika cascade sudah diatur

        // Hapus data lowongan dari database
        $job->delete();

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }
}

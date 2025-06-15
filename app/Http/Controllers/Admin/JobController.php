<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\DisabilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Disarankan untuk logging error
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        return view('admin.jobs.create', compact('disabilityCategories'));
    }

    /**
     * Store a newly created resource in storage.
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        // 2. Upload gambar ke Cloudinary
        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                // 'dira-jobs/jobs' adalah nama folder di Cloudinary
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'dira-jobs/jobs'
                ])->getSecurePath();

                $imageUrl = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Cloudinary Upload Failed: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload gambar. Silakan coba lagi.');
            }
        }

        // 3. Simpan data lowongan ke database
        $job = Auth::user()->postedJobs()->create([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $imageUrl, // Simpan URL dari Cloudinary
        ]);

        // 4. Simpan relasi ke kategori disabilitas
        if ($job && !empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        }

        // 5. Redirect ke halaman index lowongan
        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        $job->load('disabilityCategories', 'admin');
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        $job->load('disabilityCategories');
        return view('admin.jobs.edit', compact('job', 'disabilityCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after_or_equal:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        $imageUrl = $job->image; // Ambil URL gambar lama sebagai default
        if ($request->hasFile('image')) {
            // Hapus gambar lama di Cloudinary jika ada
            if ($job->image) {
                try {
                    // Ekstrak Public ID dari URL Cloudinary untuk dihapus
                    $pathInfo = pathinfo(parse_url($job->image, PHP_URL_PATH));
                    $publicIdWithFolder = 'dira-jobs/jobs/' . $pathInfo['filename'];
                    Cloudinary::destroy($publicIdWithFolder);
                } catch (\Exception $e) {
                    Log::error('Gagal menghapus gambar lama di Cloudinary: ' . $e->getMessage());
                    // Proses tetap lanjut meskipun gagal hapus
                }
            }

            // Upload gambar baru
            try {
                 $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'dira-jobs/jobs'
                ])->getSecurePath();
                $imageUrl = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Cloudinary Upload Failed during update: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload gambar baru. Silakan coba lagi.');
            }
        }

        // Gabungkan data yang divalidasi dengan URL gambar yang baru atau lama
        $updateData = array_merge($validatedData, ['image' => $imageUrl]);
        $job->update($updateData);

        if (!empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        } else {
            $job->disabilityCategories()->detach();
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        // Hapus gambar dari Cloudinary jika ada
        if ($job->image) {
            try {
                $pathInfo = pathinfo(parse_url($job->image, PHP_URL_PATH));
                $publicIdWithFolder = 'dira-jobs/jobs/' . $pathInfo['filename'];
                Cloudinary::destroy($publicIdWithFolder);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus gambar dari Cloudinary saat delete: ' . $e->getMessage());
                // Proses hapus data dari DB tetap lanjut
            }
        }

        // Hapus data lowongan dari database
        $job->delete();

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\DisabilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk mencatat error
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // <-- Import Cloudinary

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        return view('admin.jobs.create', compact('disabilityCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after_or_equal:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Batas 10MB
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                // Upload ke Cloudinary dan dapatkan URL amannya
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'dira-jobs/jobs' // Folder di Cloudinary
                ])->getSecurePath();

                $imageUrl = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Cloudinary Upload Failed: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload gambar. Silakan coba lagi.');
            }
        }

        $job = Auth::user()->postedJobs()->create([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $imageUrl, // Simpan URL dari Cloudinary
        ]);

        if ($job && !empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil ditambahkan.');
    }

    public function show(Job $job)
    {
        $job->load('disabilityCategories', 'admin');
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        $job->load('disabilityCategories');
        return view('admin.jobs.edit', compact('job', 'disabilityCategories'));
    }

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

        $imageUrl = $job->image;
        if ($request->hasFile('image')) {
            if ($job->image) {
                try {
                    // Ekstrak Public ID dari URL Cloudinary untuk dihapus
                    $pathInfo = pathinfo(parse_url($job->image, PHP_URL_PATH));
                    $publicIdWithFolder = 'dira-jobs/jobs/' . $pathInfo['filename'];
                    Cloudinary::destroy($publicIdWithFolder);
                } catch (\Exception $e) {
                    Log::error('Gagal menghapus gambar lama di Cloudinary: ' . $e->getMessage());
                }
            }

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

        $job->update(['image' => $imageUrl] + $validatedData);

        if (!empty($validatedData['disability_categories'])) {
            $job->disabilityCategories()->sync($validatedData['disability_categories']);
        } else {
            $job->disabilityCategories()->detach();
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    public function destroy(Job $job)
    {
        if ($job->image) {
            try {
                $pathInfo = pathinfo(parse_url($job->image, PHP_URL_PATH));
                $publicIdWithFolder = 'dira-jobs/jobs/' . $pathInfo['filename'];
                Cloudinary::destroy($publicIdWithFolder);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus gambar dari Cloudinary saat delete: ' . $e->getMessage());
            }
        }
        $job->delete();
        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }
}

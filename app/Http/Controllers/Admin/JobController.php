<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\DisabilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // Tambahkan Cloudinary

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        $uploadedFileUrl = null;
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        }

        $job = Auth::user()->postedJobs()->create([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $uploadedFileUrl,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'exists:disability_categories,id'
        ]);

        $uploadedFileUrl = $job->image;
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        }

        $job->update([
            'title' => $validatedData['title'],
            'company' => $validatedData['company'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'deadline' => $validatedData['deadline'],
            'image' => $uploadedFileUrl,
        ]);

        if ($job && isset($validatedData['disability_categories']) && is_array($validatedData['disability_categories'])) {
    $job->disabilityCategories()->sync($validatedData['disability_categories']);
        } else {
            $job->disabilityCategories()->detach();
        }

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
                         ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }
}

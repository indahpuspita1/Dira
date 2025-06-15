<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Disarankan untuk logging error
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel.
     */
    public function index()
    {
        $articles = Article::with('admin')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Batas 10MB
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            try {
                // Upload ke Cloudinary dan dapatkan URL amannya
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'dira-jobs/articles' // Folder spesifik untuk gambar artikel
                ])->getSecurePath();

                $imageUrl = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Cloudinary Upload Failed for Article: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload gambar artikel. Silakan coba lagi.');
            }
        }

        // Menggunakan relasi dari User (admin) untuk menyimpan data
        Auth::user()->postedArticles()->create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'image' => $imageUrl, // Simpan URL dari Cloudinary
        ]);

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu artikel.
     */
    public function show(Article $article)
    {
        $article->load('admin');
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Memperbarui artikel di database.
     */
    public function update(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Gambar opsional saat update
        ]);

        $imageUrl = $article->image; // Ambil URL gambar lama sebagai default
        if ($request->hasFile('image')) {
            // Hapus gambar lama di Cloudinary jika ada
            if ($article->image) {
                try {
                    $pathParts = explode('/', parse_url($article->image, PHP_URL_PATH));
                    $publicIdWithFolder = 'dira-jobs/articles/' . pathinfo(end($pathParts), PATHINFO_FILENAME);
                    Cloudinary::destroy($publicIdWithFolder);
                } catch (\Exception $e) {
                    Log::error('Gagal menghapus gambar artikel lama di Cloudinary: ' . $e->getMessage());
                }
            }

            // Upload gambar baru
            try {
                 $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => 'dira-jobs/articles'
                ])->getSecurePath();
                $imageUrl = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Cloudinary Upload Failed for Article update: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload gambar baru. Silakan coba lagi.');
            }
        }

        $article->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'image' => $imageUrl,
        ]);

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(Article $article)
    {
        // Hapus gambar dari Cloudinary jika ada
        if ($article->image) {
            try {
                $pathParts = explode('/', parse_url($article->image, PHP_URL_PATH));
                $publicIdWithFolder = 'dira-jobs/articles/' . pathinfo(end($pathParts), PATHINFO_FILENAME);
                Cloudinary::destroy($publicIdWithFolder);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus gambar artikel dari Cloudinary saat delete: ' . $e->getMessage());
            }
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil dihapus.');
    }
}

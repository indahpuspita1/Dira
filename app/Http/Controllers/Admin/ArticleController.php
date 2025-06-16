<?php

namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller; // Pastikan ini ada
    use App\Models\Article;
    use App\Models\User; // Meskipun tidak wajib untuk operasi dasar, baik untuk diketahui
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    

    class ArticleController extends Controller
    {
        /**
         * Menampilkan daftar artikel.
         */
        public function index()
        {
            $articles = Article::with('admin')->latest()->paginate(10); // Ambil juga data admin pembuat
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
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Gambar wajib
            ], [
                'title.required' => 'Judul artikel wajib diisi.',
                'content.required' => 'Isi artikel wajib diisi.',
                'image.required' => 'Gambar artikel wajib diupload.',
                'image.image' => 'File yang diupload harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
                'image.max' => 'Ukuran gambar maksimal 10MB.',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('article_images', 'public');
            }

            // Menggunakan relasi dari User (admin)
            Auth::user()->postedArticles()->create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.articles.index')
                             ->with('success', 'Artikel berhasil ditambahkan.');
        }

        /**
         * Menampilkan detail satu artikel.
         * (Opsional untuk admin, tapi baik untuk ada)
         */
        public function show(Article $article)
        {
            $article->load('admin'); // Load relasi admin
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar opsional saat update
            ]);

            $imagePath = $article->image; // Simpan path gambar lama
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada dan jika gambar baru diupload
                if ($article->image) {
                    Storage::disk('public')->delete($article->image);
                }
                $imagePath = $request->file('image')->store('article_images', 'public');
            }

            $article->update([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.articles.index')
                             ->with('success', 'Artikel berhasil diperbarui.');
        }

        /**
         * Menghapus artikel dari database.
         */
        public function destroy(Article $article)
        {
            // Hapus gambar dari storage jika ada
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $article->delete();

            return redirect()->route('admin.articles.index')
                             ->with('success', 'Artikel berhasil dihapus.');
        }
    }
    
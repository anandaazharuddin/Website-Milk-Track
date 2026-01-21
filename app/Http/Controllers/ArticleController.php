<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // ========== SUPER ADMIN ONLY ==========
    
    /**
     * Admin: List artikel (CRUD)
     */
    public function index()
    {
        $articles = Article::with('author')->latest()->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Admin: Form create
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Admin: Store
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'excerpt', 'content']);
        $data['is_published'] = $request->has('is_published') ? true : false;
        $data['author_id'] = auth()->id();
        
        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        $data['slug'] = $slug;

        // Upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Admin: Form edit
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Admin: Update
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'excerpt', 'content']);
        $data['is_published'] = $request->has('is_published') ? true : false;
        
        // Generate unique slug (exclude current article)
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        
        while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        $data['slug'] = $slug;

        // Upload image baru (hapus yang lama)
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil diupdate!');
    }

    /**
     * Admin: Delete
     */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    // ========== ADMIN POS (READ ONLY) ==========
    
    /**
     * List artikel untuk Admin Pos (READ ONLY)
     */
 /**
 * List artikel untuk Admin Pos (READ ONLY)
 */
public function listForAdminPos()
{
    $articles = Article::where('is_published', true)
        ->with('author')
        ->latest()
        ->get();
    
    return view('articles.list-readonly', compact('articles'));
}

    // ========== PUBLIC API ==========
    
    /**
     * Public: Show article detail (Web View & API)
     */
    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->with('author')
            ->firstOrFail();
        
        // Check if it's an API request
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => $article->excerpt,
                    'content' => $article->content,
                    'image_url' => $article->image_url,
                    'author' => $article->author->name,
                    'created_at' => $article->created_at->locale('id')->isoFormat('dddd, D MMMM Y'),
                    'created_at_raw' => $article->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        }
        
        // Return web view
        return view('articles.show', compact('article'));
    }
}
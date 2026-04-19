<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('date_publication', 'desc')->get();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
            'extrait' => 'nullable|string',
            'image_une' => 'nullable|image|max:2048',
            'date_publication' => 'required|date',
            'type' => 'required|in:blog,reussite,partenariat',
            'publie' => 'nullable|boolean',
        ]);

        $data = $request->only(['titre', 'contenu', 'extrait', 'date_publication', 'type']);
        $data['slug'] = Str::slug($request->titre) . '-' . uniqid();
        $data['publie'] = $request->has('publie');

        if ($request->hasFile('image_une')) {
            $path = $request->file('image_une')->store('articles', 'public');
            $data['image_une'] = $path;
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article créé.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
            'extrait' => 'nullable|string',
            'image_une' => 'nullable|image|max:2048',
            'date_publication' => 'required|date',
            'type' => 'required|in:blog,reussite,partenariat',
            'publie' => 'nullable|boolean',
        ]);

        $data = $request->only(['titre', 'contenu', 'extrait', 'date_publication', 'type']);
        $data['publie'] = $request->has('publie');
        if ($request->titre != $article->titre) {
            $data['slug'] = Str::slug($request->titre) . '-' . uniqid();
        }

        if ($request->hasFile('image_une')) {
            if ($article->image_une) Storage::disk('public')->delete($article->image_une);
            $path = $request->file('image_une')->store('articles', 'public');
            $data['image_une'] = $path;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        if ($article->image_une) Storage::disk('public')->delete($article->image_une);
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé.');
    }
}
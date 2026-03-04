<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\BlogPost::with('category', 'author')->latest()->paginate(15);
        return view('admin.blog.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = \App\Models\BlogCategory::all();
        return view('admin.blog.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'feature_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('feature_image')) {
            $validated['feature_image'] = $request->file('feature_image')->store('blog', 'public');
        }

        \App\Models\BlogPost::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(\App\Models\BlogPost $blog)
    {
        // Parameter name is 'blog' because of resource name 'blog'
        $post = $blog;
        $categories = \App\Models\BlogCategory::all();
        return view('admin.blog.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, \App\Models\BlogPost $blog)
    {
        $post = $blog;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug,' . $post->id,
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'feature_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('feature_image')) {
            if ($post->feature_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($post->feature_image);
            }
            $validated['feature_image'] = $request->file('feature_image')->store('blog', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(\App\Models\BlogPost $blog)
    {
        $post = $blog;
        if ($post->feature_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->feature_image);
        }
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryItemController extends Controller
{
    public function index()
    {
        $items = \App\Models\GalleryItem::latest()->paginate(20);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'category' => 'required|string|max:255', // e.g., Projects, Products, Store
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        \App\Models\GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item added successfully.');
    }

    public function edit(\App\Models\GalleryItem $gallery)
    {
        $item = $gallery;
        return view('admin.gallery.edit', compact('item'));
    }

    public function update(Request $request, \App\Models\GalleryItem $gallery)
    {
        $item = $gallery;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'category' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        $item->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(\App\Models\GalleryItem $gallery)
    {
        $item = $gallery;
        if ($item->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted successfully.');
    }
}

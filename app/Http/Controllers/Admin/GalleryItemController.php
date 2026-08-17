<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryItemController extends Controller
{
    use ValidatesImageUploads;

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
            'image' => $this->imageRules(required: true),
            'category' => 'required|string|max:255', // e.g., Projects, Products, Store
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request, 'image', 'gallery');
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
            'image' => $this->imageRules(),
            'category' => 'required|string|max:255',
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $oldImage = $item->image;
            $validated['image'] = $this->storeImage($request, 'image', 'gallery');

            // Prune the replaced file only once the new one is safely written.
            if ($oldImage) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    use ValidatesImageUploads;

    /** Existing rows already point at uploads/banners/, so new files join them. */
    private const IMAGE_DIR = 'uploads/banners';

    public function index()
    {
        $banners = \App\Models\Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => $this->imageRules(required: true),
        ], $this->imageMessages());

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request, 'image', self::IMAGE_DIR);
        }

        \App\Models\Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(\App\Models\Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, \App\Models\Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'image' => $this->imageRules(),
        ], $this->imageMessages());

        $data = $request->all();

        if ($request->hasFile('image')) {
            $oldImage = $banner->image;
            $data['image'] = $this->storeImage($request, 'image', self::IMAGE_DIR);

            // Only prune the replaced file once the new one is safely written.
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(\App\Models\Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

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
            // Optional portrait crop; the hero falls back to `image` when absent.
            'image_mobile' => $this->imageRules(),
        ], $this->imageMessages() + $this->imageMessages('image_mobile'));

        $data = $request->all();

        foreach (['image', 'image_mobile'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeImage($request, $field, self::IMAGE_DIR);
            }
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
            'image_mobile' => $this->imageRules(),
        ], $this->imageMessages() + $this->imageMessages('image_mobile'));

        $data = $request->all();

        foreach (['image', 'image_mobile'] as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $replaced = $banner->{$field};
            $data[$field] = $this->storeImage($request, $field, self::IMAGE_DIR);

            // Only prune the replaced file once the new one is safely written.
            if ($replaced) {
                Storage::disk('public')->delete($replaced);
            }
        }

        // Explicit opt-out, so a mobile crop can be removed without replacing it.
        if ($request->boolean('remove_image_mobile') && ! $request->hasFile('image_mobile')) {
            if ($banner->image_mobile) {
                Storage::disk('public')->delete($banner->image_mobile);
            }
            $data['image_mobile'] = null;
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(\App\Models\Banner $banner)
    {
        foreach (array_filter([$banner->image, $banner->image_mobile]) as $path) {
            Storage::disk('public')->delete($path);
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

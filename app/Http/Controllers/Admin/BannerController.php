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
        // Same order the homepage slider uses, so the list reflects reality.
        $banners = \App\Models\Banner::ordered()->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Swap a banner with its neighbour, for the up/down arrows on the index.
     *
     * Swapping the two `sort_order` values (rather than incrementing) keeps the
     * sequence stable and needs no renumbering pass. Both saves fire the model's
     * `saved` event, so the cached `banners` key is invalidated either way.
     */
    public function move(\App\Models\Banner $banner, string $direction)
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 404);

        $neighbour = \App\Models\Banner::query()
            ->when($direction === 'up',
                // Strictly earlier in the ordering, taking the closest one.
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '<', $banner->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $banner->sort_order)
                                                ->where('id', '<', $banner->id)))
                    ->orderByDesc('sort_order')->orderByDesc('id'),
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '>', $banner->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $banner->sort_order)
                                                ->where('id', '>', $banner->id)))
                    ->orderBy('sort_order')->orderBy('id'))
            ->first();

        if (! $neighbour) {
            return back()->with('error', 'That banner is already at the '
                . ($direction === 'up' ? 'top' : 'bottom') . ' of the list.');
        }

        // Ties are possible on legacy rows, so a plain value swap could be a no-op.
        // Assigning each other's position and nudging on equality guarantees movement.
        $mine = $banner->sort_order;
        $theirs = $neighbour->sort_order;

        if ($mine === $theirs) {
            $theirs = $direction === 'up' ? $mine - 1 : $mine + 1;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($banner, $neighbour, $mine, $theirs) {
            $banner->update(['sort_order' => $theirs]);
            $neighbour->update(['sort_order' => $mine]);
        });

        return back()->with('success', 'Banner order updated.');
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
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages() + $this->imageMessages('image_mobile'));

        $data = $request->all();

        // Blank means "put it at the end" rather than "position 0" (which would
        // silently jump a new banner to the front of the slider).
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : \App\Models\Banner::nextSortOrder();

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
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages() + $this->imageMessages('image_mobile'));

        $data = $request->all();

        // Leave the position untouched if the field came back empty.
        if (! $request->filled('sort_order')) {
            unset($data['sort_order']);
        }

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

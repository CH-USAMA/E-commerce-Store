<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Special;
use App\Support\ImageThumbnailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpecialController extends Controller
{
    use ValidatesImageUploads;

    private const IMAGE_DIR = 'uploads/specials';

    /** Settings key for the /specials page header background. */
    public const HERO_SETTING = 'specials_hero_image';

    public function index()
    {
        // Same order the storefront uses, so the list reflects reality.
        $specials = Special::ordered()->get();
        $heroImage = Setting::where('key', self::HERO_SETTING)->value('value');

        return view('admin.specials.index', compact('specials', 'heroImage'));
    }

    /**
     * Swap a special with its neighbour, for the up/down arrows on the index.
     *
     * Swapping the two `sort_order` values (rather than incrementing) keeps the
     * sequence stable and needs no renumbering pass. Both saves fire the model's
     * `saved` event, so the cached `specials` key is invalidated either way.
     */
    public function move(Special $special, string $direction)
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 404);

        $neighbour = Special::query()
            ->when($direction === 'up',
                // Strictly earlier in the ordering, taking the closest one.
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '<', $special->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $special->sort_order)
                                                ->where('id', '<', $special->id)))
                    ->orderByDesc('sort_order')->orderByDesc('id'),
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '>', $special->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $special->sort_order)
                                                ->where('id', '>', $special->id)))
                    ->orderBy('sort_order')->orderBy('id'))
            ->first();

        if (! $neighbour) {
            return back()->with('error', 'That special is already at the '
                . ($direction === 'up' ? 'top' : 'bottom') . ' of the list.');
        }

        // Ties are possible on legacy rows, so a plain value swap could be a no-op.
        $mine = $special->sort_order;
        $theirs = $neighbour->sort_order;

        if ($mine === $theirs) {
            $theirs = $direction === 'up' ? $mine - 1 : $mine + 1;
        }

        DB::transaction(function () use ($special, $neighbour, $mine, $theirs) {
            $special->update(['sort_order' => $theirs]);
            $neighbour->update(['sort_order' => $mine]);
        });

        return back()->with('success', 'Special order updated.');
    }

    public function create()
    {
        return view('admin.specials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_full' => $this->imageRules(required: true),
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages('image_full'));

        $data = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'is_active' => $request->boolean('is_active'),
            // Blank means "put it at the end" rather than "position 0", which
            // would silently jump a new special to the front of the grid.
            'sort_order' => $request->filled('sort_order')
                ? (int) $request->input('sort_order')
                : Special::nextSortOrder(),
        ];

        $data['image_full'] = $this->storeImage($request, 'image_full', self::IMAGE_DIR);
        $data['image'] = ImageThumbnailer::generate($data['image_full'], self::IMAGE_DIR);

        Special::create($data);

        return redirect()->route('admin.specials.index')
            ->with('success', 'Special created successfully.'.$this->thumbnailNote($data['image']));
    }

    public function edit(Special $special)
    {
        return view('admin.specials.edit', compact('special'));
    }

    public function update(Request $request, Special $special)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_full' => $this->imageRules(),
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages('image_full'));

        $data = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('sort_order')) {
            $data['sort_order'] = (int) $request->input('sort_order');
        }

        $note = '';

        if ($request->hasFile('image_full')) {
            $replacedFull = $special->image_full;
            $replacedThumb = $special->image;

            $data['image_full'] = $this->storeImage($request, 'image_full', self::IMAGE_DIR);
            $data['image'] = ImageThumbnailer::generate($data['image_full'], self::IMAGE_DIR);
            $note = $this->thumbnailNote($data['image']);

            // Only delete what this app wrote. The three seeded rows point at
            // legacy public/images/ files that are tracked in git and shared with
            // other pages — removing those would break more than this record.
            $this->deleteIfOwned($replacedFull);
            $this->deleteIfOwned($replacedThumb);
        }

        $special->update($data);

        return redirect()->route('admin.specials.index')
            ->with('success', 'Special updated successfully.'.$note);
    }

    public function destroy(Special $special)
    {
        $this->deleteIfOwned($special->image_full);
        $this->deleteIfOwned($special->image);

        $special->delete();

        return redirect()->route('admin.specials.index')->with('success', 'Special deleted successfully.');
    }

    /**
     * Replace the /specials page header background.
     *
     * Stored as a setting rather than a column because it belongs to the page, not
     * to any one special. Lives on this screen instead of System Settings so it is
     * found where it is used.
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_image' => $this->imageRules(required: true),
        ], $this->imageMessages('hero_image'));

        $previous = Setting::where('key', self::HERO_SETTING)->value('value');

        $path = $this->storeImage($request, 'hero_image', self::IMAGE_DIR);

        Setting::updateOrCreate(['key' => self::HERO_SETTING], ['value' => $path]);

        $this->deleteIfOwned($previous);

        // The hero has its own cache key on the storefront — no Special row changed,
        // so FlushesContentCache would not fire.
        Cache::forget('specials_hero');

        return back()->with('success', 'Specials page header image updated.');
    }

    /**
     * Delete a stored file only when this app wrote it.
     *
     * Uploads land under uploads/; anything else is a legacy asset committed to
     * public/images/ and possibly shared with another page — the specials hero and
     * the order-tracking page both referenced the same file before this change.
     */
    private function deleteIfOwned(?string $path): void
    {
        if (filled($path) && str_starts_with($path, 'uploads/')) {
            Storage::disk('public')->delete($path);
        }
    }

    /** Tell the admin when the grid will be serving the full flyer. */
    private function thumbnailNote(?string $thumbnail): string
    {
        return $thumbnail === null
            ? ' Note: a compressed thumbnail could not be generated, so the grid will'
                . ' show the full-size image. The page still works, but will load slower.'
            : '';
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ValidatesImageUploads;

    /**
     * Categories as a tree, in the order the storefront renders them.
     *
     * Deliberately NOT paginated. The list carries up/down arrows and an arrow is
     * meaningless when its neighbour sits on another page — a move would look like
     * it did nothing until you flipped pages. Showing each parent with its children
     * nested underneath also makes the per-parent ordering visible: children are
     * ordered inside their parent, never against an unrelated branch.
     */
    public function index()
    {
        $categories = \App\Models\Category::topLevel()->ordered()->with('children')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Swap a category with its neighbouring sibling, for the up/down arrows.
     *
     * The order is per-parent, so the neighbour search is confined to rows sharing
     * this row's `parent_id`; a top-level category can never trade places with
     * someone else's child. Swapping the two `sort_order` values (rather than
     * incrementing) keeps the sequence stable and needs no renumbering pass. Both
     * saves fire the model's `saved` event, so the cached `categories_top` key is
     * invalidated either way.
     */
    public function move(\App\Models\Category $category, string $direction)
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 404);

        $neighbour = \App\Models\Category::query()
            ->where('parent_id', $category->parent_id)
            ->when($direction === 'up',
                // Strictly earlier in the ordering, taking the closest one.
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '<', $category->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $category->sort_order)
                                                ->where('id', '<', $category->id)))
                    ->orderByDesc('sort_order')->orderByDesc('id'),
                fn ($q) => $q->where(fn ($w) => $w
                        ->where('sort_order', '>', $category->sort_order)
                        ->orWhere(fn ($t) => $t->where('sort_order', $category->sort_order)
                                                ->where('id', '>', $category->id)))
                    ->orderBy('sort_order')->orderBy('id'))
            ->first();

        if (! $neighbour) {
            return back()->with('error', 'That category is already at the '
                . ($direction === 'up' ? 'top' : 'bottom')
                . ' of its list.');
        }

        // Ties are possible on legacy rows, so a plain value swap could be a no-op.
        // Assigning each other's position and nudging on equality guarantees movement.
        $mine = $category->sort_order;
        $theirs = $neighbour->sort_order;

        if ($mine === $theirs) {
            $theirs = $direction === 'up' ? $mine - 1 : $mine + 1;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($category, $neighbour, $mine, $theirs) {
            $category->update(['sort_order' => $theirs]);
            $neighbour->update(['sort_order' => $mine]);
        });

        return back()->with('success', 'Category order updated.');
    }

    public function create()
    {
        $parents = \App\Models\Category::whereNull('parent_id')->ordered()->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => $this->imageRules(),
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages());

        // Blank means "put it at the end of its sibling group" rather than
        // "position 0" (which would silently jump a new category to the front of
        // the homepage grid).
        $validated['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : \App\Models\Category::nextSortOrder($validated['parent_id'] ?? null);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request, 'image', 'categories');
        }

        \App\Models\Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(\App\Models\Category $category)
    {
        $parents = \App\Models\Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->ordered()
            ->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, \App\Models\Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'image' => $this->imageRules(),
            'sort_order' => 'nullable|integer|min:0',
        ], $this->imageMessages());

        $newParentId = $validated['parent_id'] ?? null;

        if ($request->filled('sort_order')) {
            $validated['sort_order'] = (int) $request->input('sort_order');
        } elseif ($newParentId != $category->parent_id) {
            // Moved to a different parent: its old position belongs to the group it
            // just left and means nothing here, so land it at the end of the new one.
            $validated['sort_order'] = \App\Models\Category::nextSortOrder($newParentId);
        } else {
            // Blank and same parent: keep the position it already has.
            unset($validated['sort_order']);
        }

        if ($request->hasFile('image')) {
            $oldImage = $category->image;
            $validated['image'] = $this->storeImage($request, 'image', 'categories');

            if ($oldImage) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\Category $category)
    {
        if ($category->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}

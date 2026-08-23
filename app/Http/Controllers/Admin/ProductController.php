<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ValidatesImageUploads;

    public function index()
    {
        $products = \App\Models\Product::with('category', 'subcategory', 'brand')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::topLevel()->ordered()->with('children')->get();
        $brands = \App\Models\Brand::all();
        $stores = \App\Models\Store::all();
        return view('admin.products.create', compact('categories', 'brands', 'stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'is_featured' => 'boolean',
            'is_top_selling' => 'boolean',
            'is_new_arrival' => 'boolean',
            'status' => 'required|in:active,inactive',
            'image' => $this->imageRules(),
            'stocks' => 'nullable|array',
            'stocks.*' => 'numeric',
            'has_variants' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.label' => 'nullable|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.is_active' => 'nullable|boolean',
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request, 'image', 'products');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_top_selling'] = $request->has('is_top_selling');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');

        $validated['has_variants'] = $request->boolean('has_variants');

        $product = \App\Models\Product::create($validated);

        if ($error = $this->syncVariants($product, $request)) {
            return redirect()->route('admin.products.edit', $product)->with('error', $error);
        }

        // Handle initial stocks
        if ($request->has('stocks')) {
            foreach ($request->stocks as $storeId => $quantity) {
                if ($quantity > 0) {
                    \App\Models\ProductStoreStock::create([
                        'product_id' => $product->id,
                        'store_id' => $storeId,
                        'quantity' => $quantity,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(\App\Models\Product $product)
    {
        $categories = \App\Models\Category::topLevel()->ordered()->with('children')->get();
        $brands = \App\Models\Brand::all();
        $stores = \App\Models\Store::with([
            'stocks' => function ($q) use ($product) {
                $q->where('product_id', $product->id);
            }
        ])->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'stores'));
    }

    public function update(Request $request, \App\Models\Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id,
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'is_featured' => 'boolean',
            'is_top_selling' => 'boolean',
            'is_new_arrival' => 'boolean',
            'status' => 'required|in:active,inactive',
            'image' => $this->imageRules(),
            // The edit form posts nested rows — stocks[<storeId>][quantity] etc. — unlike
            // the create form's flat stocks[<storeId>]. A `stocks.*' => 'numeric'` rule
            // here rejected every save as soon as one store existed.
            'stocks' => 'nullable|array',
            'stocks.*' => 'array',
            'stocks.*.quantity' => 'nullable|numeric|min:0',
            'stocks.*.incoming' => 'nullable|numeric|min:0',
            'stocks.*.reserved' => 'nullable|numeric|min:0',
            'stocks.*.damaged' => 'nullable|numeric|min:0',
            'has_variants' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.label' => 'nullable|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.is_active' => 'nullable|boolean',
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $oldImage = $product->image;
            $validated['image'] = $this->storeImage($request, 'image', 'products');

            // Prune the replaced file only once the new one is safely written.
            if ($oldImage) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_top_selling'] = $request->has('is_top_selling');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');

        $validated['has_variants'] = $request->boolean('has_variants');

        $product->update($validated);

        if ($error = $this->syncVariants($product, $request)) {
            return back()->withInput()->with('error', $error);
        }

        // Update stocks (WMS Aware)
        if ($request->has('stocks')) {
            foreach ($request->stocks as $storeId => $stockData) {
                \App\Models\ProductStoreStock::updateOrCreate(
                    ['product_id' => $product->id, 'store_id' => $storeId],
                    [
                        'quantity' => $stockData['quantity'] ?? 0,
                        'incoming' => $stockData['incoming'] ?? 0,
                        'reserved' => $stockData['reserved'] ?? 0,
                        'damaged' => $stockData['damaged'] ?? 0,
                    ]
                );
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Persist the size rows posted by the product form.
     *
     * Returns null on success, or a message to show the admin. Deliberately does
     * NOT throw a validation exception: the product itself has already saved by
     * this point, so failing hard would leave the admin thinking nothing was kept.
     *
     * Rows are matched by their hidden `id` so editing a size keeps its identity —
     * order history joins on it, and delete-and-recreate would orphan those rows.
     * A row present in the database but absent from the submission was removed in
     * the browser, so it is deleted here.
     *
     * Unticking "has sizes" does NOT delete the sizes. They stop being offered
     * (offersVariants() checks the flag) and come back intact when it is re-ticked,
     * which is what a seasonal range needs.
     */
    private function syncVariants(\App\Models\Product $product, Request $request): ?string
    {
        $rows = collect($request->input('variants', []))
            // Blank template rows are normal: the form ships one empty row to type
            // into, and removing a row in the browser can leave a gap.
            ->filter(fn ($row) => filled($row['label'] ?? null) || filled($row['price'] ?? null))
            ->values();

        if (! $request->boolean('has_variants')) {
            // Keep the rows, just stop offering them.
            return null;
        }

        if ($rows->isEmpty()) {
            $product->update(['has_variants' => false]);

            return 'No sizes were entered, so this product was saved as a single product.';
        }

        $incomplete = $rows->first(fn ($row) => blank($row['label'] ?? null) || blank($row['price'] ?? null));

        if ($incomplete) {
            $product->update(['has_variants' => false]);

            return 'Every size needs both a name and a price, so this product was saved '
                . 'as a single product. Re-open it to finish adding the sizes.';
        }

        $labels = $rows->map(fn ($row) => trim($row['label']));

        if ($labels->count() !== $labels->map(fn ($l) => mb_strtolower($l))->unique()->count()) {
            $product->update(['has_variants' => false]);

            return 'Two sizes had the same name. Sizes must be unique per product, so '
                . 'this product was saved as a single product.';
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($product, $rows) {
            $keptIds = [];

            foreach ($rows as $index => $row) {
                $attributes = [
                    'label' => trim($row['label']),
                    'sku' => filled($row['sku'] ?? null) ? trim($row['sku']) : null,
                    'price' => (float) $row['price'],
                    // An unchecked checkbox posts nothing, so absence means false.
                    'is_active' => (bool) ($row['is_active'] ?? false),
                    // Position comes from the row order on screen, not from the
                    // label — sizes sort badly as text (50MM after 150MM).
                    'sort_order' => $index + 1,
                ];

                $existing = filled($row['id'] ?? null)
                    ? $product->variants()->whereKey($row['id'])->first()
                    : null;

                if ($existing) {
                    $existing->update($attributes);
                    $keptIds[] = $existing->id;
                } else {
                    $keptIds[] = $product->variants()->create($attributes)->id;
                }
            }

            // Rows the admin removed in the browser.
            $product->variants()->whereNotIn('id', $keptIds)->delete();
        });

        return null;
    }

    public function destroy(\App\Models\Product $product)
    {
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function export()
    {
        $products = \App\Models\Product::with('category', 'brand', 'stocks.store')->get();
        $csvHeader = [
            'ID', 'Name', 'Slug', 'SKU', 'Description', 'Price', 'VAT Rate', 
            'Category', 'Brand', 'Featured', 
            'Store Name', 'Stock Physical', 'Stock Incoming', 'Stock Reserved', 'Stock Damaged'
        ];

        $callback = function () use ($products, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($products as $product) {
                // If product has multiple stocks, we'll export one row per stock to make re-importing easier
                if ($product->stocks->count() > 0) {
                    foreach ($product->stocks as $stock) {
                        fputcsv($file, [
                            $product->id,
                            $product->name,
                            $product->slug,
                            $product->sku,
                            $product->description,
                            $product->price,
                            $product->vat_rate,
                            $product->category ? $product->category->name : '',
                            $product->brand ? $product->brand->name : '',
                            $product->is_featured ? '1' : '0',
                            $stock->store ? $stock->store->name : '',
                            $stock->quantity,
                            $stock->incoming,
                            $stock->reserved,
                            $stock->damaged,
                        ]);
                    }
                } else {
                    fputcsv($file, [
                        $product->id, $product->name, $product->slug, $product->sku, $product->description, 
                        $product->price, $product->vat_rate, 
                        $product->category ? $product->category->name : '',
                        $product->brand ? $product->brand->name : '',
                        $product->is_featured ? '1' : '0',
                        '', '0', '0', '0', '0'
                    ]);
                }
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'products-inventory-export-' . date('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4)
                continue;

            // Map: 0:ID, 1:Name, 2:Slug, 3:SKU, 4:Description, 5:Price, 6:VAT, 7:Category, 8:Brand, 9:Featured

            // Resolve Category
            $categoryName = $row[7] ?: 'Uncategorized';
            $category = \App\Models\Category::firstOrCreate(['name' => $categoryName], ['slug' => \Illuminate\Support\Str::slug($categoryName)]);

            // Resolve Brand
            $brandId = null;
            if (!empty($row[8])) {
                $brand = \App\Models\Brand::firstOrCreate(['name' => $row[8]], ['slug' => \Illuminate\Support\Str::slug($row[8])]);
                $brandId = $brand->id;
            }

            $product = \App\Models\Product::updateOrCreate(
                ['sku' => $row[3]],
                [
                    'name' => $row[1],
                    'slug' => $row[2] ?: \Illuminate\Support\Str::slug($row[1]),
                    'description' => $row[4] ?? null,
                    'price' => (float) ($row[5] ?? 0),
                    'vat_rate' => (float) ($row[6] ?? 15),
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'is_featured' => ($row[9] ?? '0') == '1',
                ]
            );

            // Resolve Store & Stock (WMS Aware)
            if (!empty($row[10])) {
                $storeName = $row[10];
                $store = \App\Models\Store::where('name', $storeName)->first();
                if ($store) {
                    \App\Models\ProductStoreStock::updateOrCreate(
                        ['product_id' => $product->id, 'store_id' => $store->id],
                        [
                            'quantity' => (int) ($row[11] ?? 0),
                            'incoming' => (int) ($row[12] ?? 0),
                            'reserved' => (int) ($row[13] ?? 0),
                            'damaged' => (int) ($row[14] ?? 0),
                        ]
                    );
                }
            }
            $count++;
        }
        fclose($handle);

        return redirect()->back()->with('success', "Imported $count products successfully.");
    }
}

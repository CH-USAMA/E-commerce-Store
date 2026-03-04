<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('category', 'brand')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
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
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'stocks' => 'nullable|array',
            'stocks.*' => 'numeric',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = \App\Models\Product::create($validated);

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
        $categories = \App\Models\Category::all();
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
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'vat_rate' => 'required|numeric',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'stocks' => 'nullable|array',
            'stocks.*' => 'numeric',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        // Update stocks
        if ($request->has('stocks')) {
            foreach ($request->stocks as $storeId => $quantity) {
                \App\Models\ProductStoreStock::updateOrCreate(
                    ['product_id' => $product->id, 'store_id' => $storeId],
                    ['quantity' => $quantity]
                );
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
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
        $products = \App\Models\Product::with('category', 'brand')->get();
        $csvHeader = ['ID', 'Name', 'Slug', 'SKU', 'Description', 'Price', 'VAT Rate', 'Category', 'Brand', 'Featured'];

        $callback = function () use ($products, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($products as $product) {
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
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'products-export-' . date('Y-m-d') . '.csv', [
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

            \App\Models\Product::updateOrCreate(
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
            $count++;
        }
        fclose($handle);

        return redirect()->back()->with('success', "Imported $count products successfully.");
    }
}

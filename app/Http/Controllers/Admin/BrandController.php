<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use ValidatesImageUploads;

    public function index()
    {
        $brands = \App\Models\Brand::paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
            'logo' => $this->imageRules(),
        ], $this->imageMessages('logo'));

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeImage($request, 'logo', 'brands');
        }

        \App\Models\Brand::create($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(\App\Models\Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, \App\Models\Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug,' . $brand->id,
            'logo' => $this->imageRules(),
        ], $this->imageMessages('logo'));

        if ($request->hasFile('logo')) {
            $oldLogo = $brand->logo;
            $validated['logo'] = $this->storeImage($request, 'logo', 'brands');

            if ($oldLogo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(\App\Models\Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }
}

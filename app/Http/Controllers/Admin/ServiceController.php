<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesImageUploads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ValidatesImageUploads;

    public function index()
    {
        $services = \App\Models\Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:services,slug',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'image' => $this->imageRules(),
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request, 'image', 'services');
        }

        \App\Models\Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(\App\Models\Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, \App\Models\Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:services,slug,' . $service->id,
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'image' => $this->imageRules(),
        ], $this->imageMessages());

        if ($request->hasFile('image')) {
            $oldImage = $service->image;
            $validated['image'] = $this->storeImage($request, 'image', 'services');

            if ($oldImage) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(\App\Models\Service $service)
    {
        if ($service->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image);
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = \App\Models\Store::with('managers')->paginate(10);
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        $managers = \App\Models\User::where('role', 'manager')->get();
        return view('admin.stores.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:stores,slug',
            'image' => 'nullable|image|max:2048',
            'address' => 'required|string',
            'province' => 'required|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'contact_details' => 'nullable|string',
            'manager_ids' => 'nullable|array',
            'manager_ids.*' => 'exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('stores', 'public');
            $data = $validated;
            $data['image'] = $path;
        } else {
            $data = $validated;
        }

        $store = \App\Models\Store::create($data);

        if ($request->has('manager_ids')) {
            $store->managers()->sync($request->manager_ids);
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function edit(\App\Models\Store $store)
    {
        $managers = \App\Models\User::where('role', 'manager')->get();
        return view('admin.stores.edit', compact('store', 'managers'));
    }

    public function update(Request $request, \App\Models\Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:stores,slug,' . $store->id,
            'image' => 'nullable|image|max:2048',
            'address' => 'required|string',
            'province' => 'required|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'contact_details' => 'nullable|string',
            'manager_ids' => 'nullable|array',
            'manager_ids.*' => 'exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('stores', 'public');
            $data = $validated;
            $data['image'] = $path;
        } else {
            $data = $validated;
        }

        $store->update($data);

        if ($request->has('manager_ids')) {
            $store->managers()->sync($request->manager_ids);
        } else {
            $store->managers()->detach();
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(\App\Models\Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $store = auth()->user()->managedStore;

        if (!$store) {
            return view('branch.no_store');
        }

        // Get all products and join with stocks for this store
        $products = \App\Models\Product::with([
            'stocks' => function ($q) use ($store) {
                $q->where('store_id', $store->id);
            }
        ])->paginate(20);

        return view('branch.stocks.index', compact('products', 'store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->managedStore;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'incoming' => 'required|integer|min:0',
            'reserved' => 'required|integer|min:0',
            'damaged' => 'required|integer|min:0',
        ]);

        \App\Models\ProductStoreStock::updateOrCreate(
            ['store_id' => $store->id, 'product_id' => $request->product_id],
            [
                'quantity' => $request->quantity,
                'incoming' => $request->incoming,
                'reserved' => $request->reserved,
                'damaged' => $request->damaged,
            ]
        );

        return back()->with('success', 'Stock updated successfully.');
    }
}

<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreService
{
    /**
     * Finds the nearest store to a given latitude and longitude.
     * Uses the Haversine formula.
     * 
     * @param float $lat
     * @param float $lng
     * @return Store|null
     */
    public function findNearestStore(float $lat, float $lng)
    {
        // 6371 is the earth's radius in kilometers
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");

        if ($connection === 'sqlite') {
            // SQLite doesn't have math functions like acos/cos/radians/sin by default
            // Fetch all stores and calculate distance in PHP
            $stores = Store::whereNotNull('lat')
                ->whereNotNull('lng')
                ->get();

            if ($stores->isEmpty()) {
                return null;
            }

            return $stores->map(function ($store) use ($lat, $lng) {
                $theta = $lng - $store->lng;
                $dist = sin(deg2rad($lat)) * sin(deg2rad($store->lat)) + cos(deg2rad($lat)) * cos(deg2rad($store->lat)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $store->distance = $dist * 60 * 1.1515 * 1.609344; // Kilometers
                return $store;
            })->sortBy('distance')->first();
        }

        return Store::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->orderBy('distance', 'asc')
            ->first();
    }

    /**
     * Get inventory for a specific product across all stores or a specific one.
     * 
     * @param int $productId
     * @param int|null $storeId
     * @return \Illuminate\Support\Collection
     */
    public function getProductStock(int $productId, ?int $storeId = null)
    {
        $query = DB::table('product_store_stocks')
            ->where('product_id', $productId);

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return $query->get();
    }

    /**
     * Deduct stock from a store upon order fulfillment.
     * 
     * @param int $productId
     * @param int $storeId
     * @param int $quantity
     * @return bool
     */
    public function deductStock(int $productId, int $storeId, int $quantity)
    {
        $stock = DB::table('product_store_stocks')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if (!$stock || $stock->quantity < $quantity) {
            return false;
        }

        return DB::table('product_store_stocks')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->decrement('quantity', $quantity);
    }
}

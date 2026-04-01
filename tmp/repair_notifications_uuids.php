<?php

use App\Models\Order;
use Illuminate\Support\Facades\DB;

$notifications = DB::table('notifications')
    ->where('data', 'like', '%admin/orders/%')
    ->get();

$count = 0;
foreach ($notifications as $notification) {
    $data = json_decode($notification->data, true);
    
    // Check if the URL contains a numeric ID
    if (isset($data['url']) && preg_match('/admin\/orders\/(\d+)/', $data['url'], $matches)) {
        $id = $matches[1];
        $order = Order::find($id);
        
        if ($order && $order->uuid) {
            $data['url'] = str_replace("admin/orders/$id", "admin/orders/{$order->uuid}", $data['url']);
            $data['order_id'] = $order->uuid; // Update order_id to uuid while we are at it
            
            DB::table('notifications')
                ->where('id', $notification->id)
                ->update(['data' => json_encode($data)]);
            $count++;
        }
    }
}

echo "Successfully updated $count legacy notifications to use UUID routing.\n";

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use App\Notifications\MarketingNotification;
use Illuminate\Support\Facades\Notification;

class MarketingController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.marketing.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.marketing.create');
    }

    public function push(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'url' => 'nullable|url',
        ]);

        $users = User::where('role', 'user')->get();
        
        // Create Campaign Record
        $campaign = Campaign::create([
            'title' => $request->title,
            'message' => $request->message,
            'url' => $request->url,
            'status' => 'sent',
            'recipients_count' => $users->count(),
            'sent_at' => now(),
        ]);

        Notification::send($users, new MarketingNotification(
            $campaign->title,
            $campaign->message,
            $campaign->url
        ));

        return redirect()->route('admin.marketing.index')->with('success', 'Marketing campaign broadcast successfully to ' . $users->count() . ' recipients.');
    }

    public function resend(Campaign $campaign)
    {
        $users = User::where('role', 'user')->get();
        
        Notification::send($users, new MarketingNotification(
            $campaign->title,
            $campaign->message,
            $campaign->url
        ));

        $campaign->update([
            'sent_at' => now(),
            'recipients_count' => $users->count(),
        ]);

        return back()->with('success', 'Campaign resent successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return back()->with('success', 'Campaign log removed.');
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}

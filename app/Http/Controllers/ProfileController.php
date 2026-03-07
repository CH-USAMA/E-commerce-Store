<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showCompleteProfile()
    {
        // If profile is already complete, redirect to dashboard
        if (!empty(Auth::user()->phone)) {
            return redirect()->route('user.dashboard');
        }
        return view('profile.complete');
    }

    public function storeCompleteProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address_name' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->update(['phone' => $request->phone]);

        $user->addresses()->create([
            'address_name' => $request->address_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_default' => true,
        ]);

        return redirect()->intended(route('user.dashboard'))->with('success', 'Profile completed successfully! Welcome to Jabulani Group.');
    }

    public function manageAddresses()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('profile.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_name' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        $isFirstAddress = $user->addresses()->count() === 0;

        if ($request->is_default || $isFirstAddress) {
            $user->addresses()->update(['is_default' => false]);
            $isDefault = true;
        } else {
            $isDefault = false;
        }

        $user->addresses()->create([
            'address_name' => $request->address_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'New address saved to your profile.');
    }

    public function deleteAddress(Address $address)
    {
        // Ensure user owns the address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Don't allow deleting default address if there are others
        if ($address->is_default && Auth::user()->addresses()->count() > 1) {
            return back()->with('error', 'You cannot delete your default address. Set another one as default first.');
        }

        $address->delete();

        return back()->with('success', 'Address removed successfully.');
    }

    public function setDefaultAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }
}

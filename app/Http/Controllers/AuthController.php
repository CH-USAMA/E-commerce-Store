<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Address;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'shipping_address_line_1' => 'required|string',
            'shipping_address_line_2' => 'nullable|string',
            'shipping_city' => 'required|string',
            'shipping_province' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'billing_same_as_shipping' => 'nullable|boolean',
            'billing_address_line_1' => 'nullable|required_without:billing_same_as_shipping|string',
            'billing_address_line_2' => 'nullable|string',
            'billing_city' => 'nullable|required_without:billing_same_as_shipping|string',
            'billing_province' => 'nullable|required_without:billing_same_as_shipping|string',
            'billing_postal_code' => 'nullable|required_without:billing_same_as_shipping|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            // Shipping Address
            Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'address_line_1' => $request->shipping_address_line_1,
                'address_line_2' => $request->shipping_address_line_2,
                'city' => $request->shipping_city,
                'province' => $request->shipping_province,
                'postal_code' => $request->shipping_postal_code,
                'is_default' => true,
            ]);

            // Billing Address
            if ($request->has('billing_same_as_shipping')) {
                Address::create([
                    'user_id' => $user->id,
                    'type' => 'billing',
                    'address_line_1' => $request->shipping_address_line_1,
                    'address_line_2' => $request->shipping_address_line_2,
                    'city' => $request->shipping_city,
                    'province' => $request->shipping_province,
                    'postal_code' => $request->shipping_postal_code,
                    'is_default' => true,
                ]);
            } else {
                Address::create([
                    'user_id' => $user->id,
                    'type' => 'billing',
                    'address_line_1' => $request->billing_address_line_1,
                    'address_line_2' => $request->billing_address_line_2,
                    'city' => $request->billing_city,
                    'province' => $request->billing_province,
                    'postal_code' => $request->billing_postal_code,
                    'is_default' => true,
                ]);
            }

            DB::commit();
            Auth::login($user);

            if (session()->has('cart') && count(session()->get('cart')) > 0) {
                return redirect()->route('checkout');
            }
            return redirect()->url('/user/dashboard'); // Will create this route later
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function redirectByRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'manager') {
            return redirect()->route('branch.dashboard');
        }

        // Let's redirect regular customers to their new Dashboard
        return redirect()->route('user.dashboard');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update existing user with google info if not present
                $user->google_id = $googleUser->getId();
                $user->google_token = $googleUser->token;
                $user->google_refresh_token = $googleUser->refreshToken ?? $user->google_refresh_token;

                // Ensure they are marked as verified since Google has already verified them
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                $user->save();
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]);

                // Explicitly set role since it's not fillable for security
                $user->role = 'user';
                $user->save();

                // Subscribe to newsletter if new
                NewsletterSubscriber::updateOrCreate(
                    ['email' => $user->email],
                    ['name' => $user->name]
                );
            }

            Auth::login($user);

            if (empty($user->phone) || $user->addresses()->count() === 0) {
                return redirect()->route('profile.complete')->with('info', 'Welcome! Please complete your profile to continue.');
            }

            return redirect()->intended(route('user.dashboard'))->with('success', 'Logged in successfully with Google!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong during Google Login. Please try again.');
        }
    }
}

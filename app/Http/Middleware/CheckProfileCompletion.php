<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If phone is missing, redirect to profile completion
            // Excluding the profile completion routes and logout
            if (
                empty($user->phone) &&
                !$request->is('profile/complete*') &&
                !$request->is('logout') &&
                !$request->is('admin*')
            ) {
                return redirect()->route('profile.complete');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPricingEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hidePricing = \App\Models\Setting::where('key', 'hide_pricing')->value('value') === '1';

        if ($hidePricing) {
            return redirect()->route('contact')->with('info', 'Online ordering is temporarily unavailable — please contact us to place an order.');
        }

        return $next($request);
    }
}

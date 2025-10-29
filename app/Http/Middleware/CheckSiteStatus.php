<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Fetch site active status (assuming one settings row)
        $setting = Setting::first();

        // If setting found and inactive
        if ($setting && $setting->is_site_active === 'Inactive') {

            // Allow admins to access
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // If not admin — log out user
            if (Auth::check()) {
                Auth::logout();
            }

            // Show maintenance blade
            return response()->view('maintenance');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthMiddleware
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
        $notification = [
            'message' => 'You must be logged in to access this page.',
            'alert-type' => 'error'
        ];
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect('/user/login')->with($notification);
        }

        $notification2 = [
            'message' => 'User not active, please contact with administrator for activation.',
            'alert-type' => 'error'
        ];
        if (Auth::check() && Auth::user()->status === 'Inactive') {
            return redirect('/user/login')->with($notification2);
        }

        return $next($request);
    }
}

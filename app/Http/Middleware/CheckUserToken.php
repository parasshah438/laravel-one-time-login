<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if (Auth::check()) {
            $user = Auth::user();
            $sessionToken = session('user_token');

            if ($user->token !== $sessionToken) {
                Auth::logout();
                session()->flush();

                return redirect('/login')->withErrors(['error' => 'You have been logged out because your account was accessed from another device.']);
            }
        }
        return $next($request);
    }
}

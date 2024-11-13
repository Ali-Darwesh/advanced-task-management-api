<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Using ...$roles to accept multiple roles as arguments
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Ensure the user data is up to date 
            $user = User::findOrFail($user->id);

            // Check if the authenticated user has any of the required roles
            $hasRole = $user->roles()->whereIn('name', $roles)->exists();

            if ($hasRole) {
                return $next($request);  // User has one of the required roles, continue to the next middleware/route
            }
        }

        // If the user does not have the required role, redirect or abort
        return redirect('/'); // Redirect to the homepage or any URL
    }
}

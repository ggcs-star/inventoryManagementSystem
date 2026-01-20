<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is blocked.']);
        }

        if ($user->role !== $role) {
            abort(403); // show 403 page
        }

        return $next($request);
    }
}

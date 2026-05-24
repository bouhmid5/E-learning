<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = match ($role) {
            'admin' => Auth::guard('admin')->user(),
            'candidat', 'formateur' => Auth::guard('web')->user(),
            default => null,
        };

        abort_unless($user && Gate::forUser($user)->allows('access-role', $role), 403);

        return $next($request);
    }
}

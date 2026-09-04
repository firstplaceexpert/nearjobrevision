<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user()) {
                // Redirect to the appropriate dashboard based on role
                return $request->user()->isCompany()
                    ? redirect()->route('company.dashboard')
                    : redirect()->route('applicant.map');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}

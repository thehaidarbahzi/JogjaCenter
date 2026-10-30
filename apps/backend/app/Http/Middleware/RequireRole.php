<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $roles,
    ): Response {
        $user = $request->user();
        $requiredRoles = explode("|", $roles);

        if (
            !$user ||
            !$user->roles()->whereIn("name", $requiredRoles)->exists()
        ) {
            return response()->json(
                ["message" => "Forbidden: Insufficient permissions"],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Require2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->two_factor_enabled &&
            (!$user->two_factor_verified_at ||
                $user->two_factor_verified_at->addMinutes(60)->isPast())) {
            return response()->json([
                'success' => false,
                'message' => '2FA required',
                'requires_2fa' => true
            ], 403);
        }

        return $next($request);
    }
}

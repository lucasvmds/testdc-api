<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = User::current();
        abort_unless($user, 401);
        $access_token = $user->tokens()->first();
        abort_unless($access_token, 401);
        if ($access_token->expires_at) {
            if (now()->greaterThanOrEqualTo($access_token->expires_at)) {
                $access_token->delete();
                abort(401);
            }
            $access_token->update(['expires_at' => now()->addHour()]);
        }
        return $next($request);
    }
}

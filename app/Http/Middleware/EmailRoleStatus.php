<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailRoleStatus
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
        $adminEmails = [
            'admin@zmart.com.com',
            'ytlemon290@gmail.com',
        ];

        $user = Auth::user();

        if (!$user || !in_array($user->email, $adminEmails)) {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}

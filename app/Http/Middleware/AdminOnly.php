<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && (\Auth::user()->tipe_akun == User::TYPE_ADMIN))
            return $next($request);
        return redirect()->back()->withErrors('Unauthorized page!');
    }
}

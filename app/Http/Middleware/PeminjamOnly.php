<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class PeminjamOnly
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
        if (\Auth::check() && (\Auth::user()->tipe_akun == User::TYPE_PEMINJAM || \Auth::user()->tipe_akun == User::TYPE_ADMIN)) {
            if (\Auth::user()->status != User::STATUS_ACTIVE) {
                \Auth::logout();
                return redirect(route('dashboard.index'))->withErrors(['User tidak aktif!']);
            }
            return $next($request);
        }
        return redirect()->back()->withErrors('Unauthorized page!');
    }
}

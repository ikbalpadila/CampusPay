<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MahasiswaAuth
{
    public function handle(
        Request $request,
        Closure $next
    )
    {
        if (! session()->has('jwt_token')) {

            return redirect()
                ->route('mahasiswa.login');
        }

        return $next($request);
    }
}
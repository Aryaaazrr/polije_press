<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EditorNaskah
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->id_role != '3') {
            if (Auth::user()->id_role == '1') {
                return redirect('admin/dashboard');
            }
            if (Auth::user()->id_role == '2') {
                return redirect('dashboard');
            }
            if (Auth::user()->id_role == '4') {
                return redirect('editor-akuisisi/dashboard');
            }
            if (Auth::user()->id_role == '5') {
                return redirect('pengelola/dashboard');
            }
        }

        return $next($request);
    }
}

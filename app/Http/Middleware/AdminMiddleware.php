<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Autoriser l'accès aux images sans authentification
        if ($request->is('storage/*')) {
            return $next($request);
        }
        
        // Autoriser les assets
        if ($request->is('build/*')) {
            return $next($request);
        }
        
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        return $next($request);
    }
}
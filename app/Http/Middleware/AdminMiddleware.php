<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah terotentikasi dan memiliki role 'admin'
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Jika bukan admin, kembalikan respon JSON 403 Forbidden
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak. Endpoint ini hanya untuk Admin.'
        ], 403);
    }
}

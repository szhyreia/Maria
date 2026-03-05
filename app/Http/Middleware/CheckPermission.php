<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        if (!$request->user()->can($permission)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - Missing permission: ' . $permission
            ], 403);
        }

        return $next($request);
    }
}

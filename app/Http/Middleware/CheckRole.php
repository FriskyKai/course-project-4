<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next, $role)
    {
        // Получаем текущего пользователя
        $user = auth()->user();

        // Если пользователь не аутентифицирован или роль не совпадает
        if (!$user || $user->role->code !== $role) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

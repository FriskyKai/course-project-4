<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Регистрация
    public function register(RegisterRequest $request) {
        $role_user = Role::where('code', 'user')->first();

        $user = User::create([
            ...$request->validated(),
            'role_id' => $role_user->id,
        ]);

        $user->api_token = Hash::make(Str::random(60));
        $user->save();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->api_token,
        ])->setStatusCode(201);
    }

    // Аутентификация
    public function login(Request $request) {
        if (!Auth::attempt($request->only('username', 'password'))) {
            throw new ApiException('Unauthorized', 401);
        }

        $user = Auth::user();

        $user->api_token = Hash::make(Str::random(60));
        $user->save();

        // Ответ
        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->api_token
        ])->setStatusCode(200);
    }

    // Выход
    public function logout() {
        $user = Auth::user();

        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Вы вышли из системы'])->setStatusCode(200);
    }
}

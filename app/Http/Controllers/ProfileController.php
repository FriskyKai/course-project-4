<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Профиль пользователя
    public function profile() {
        $user = auth()->user();

        return response()->json([
            'user' => new UserResource($user),
            // 'files' => new FileResource($files),
        ])->setStatusCode(200);
    }

    // Редактирование профиля пользователя
    public function update(Request $request) {
        $user = auth()->user();
        $user->update($request->all());

        return response()->json([
            'user' => new UserResource($user),
            // 'files' => new FileResource($files),
        ])->setStatusCode(200);
    }
}

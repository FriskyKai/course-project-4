<?php

namespace App\Http\Controllers;


use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Профиль пользователя
    public function profile() {
        $user = auth()->user();

        $files = File::where('user_id', $user->id)->get();

        return response()->json([
            'user' => new UserResource($user),
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    // Редактирование профиля пользователя
    public function update(Request $request) {
        $user = auth()->user();
        $user->update($request->all());

        $files = File::where('user_id', $user->id)->get();

        return response()->json([
            'user' => new UserResource($user),
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }
}

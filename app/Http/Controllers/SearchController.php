<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSearchRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Поиск пользователя по имени пользователя
    public function userSearch(UserSearchRequest $request) {
        $username = $request->username; // Получаем параметр запроса 'username'

        $users = User::where('username', 'LIKE', "%{$username}%")->get();

        if ($users->isEmpty()) {
            return response()->json('No users found')->setStatusCode(404);
        }

        return response()->json([
            'users' => UserResource::collection($users)
        ])->setStatusCode(200);
    }

    // Поиск файла по названию (у пользователя)
    public function fileSearch(FileSearchRequest $request) {
        $name = $request->name; // Получаем параметр запроса 'name'

        $files = File::where('name', 'LIKE', "%{$name}%")->get();

        if ($files->isEmpty()) {
            return response()->json('No files found')->setStatusCode(404);
        }

        return response()->json([
            'files' => FileResource::collection($files)
        ])->setStatusCode(200);
    }
}

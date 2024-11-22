<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSearchRequest;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Поиск пользователя по имени
    public function userSearch(Request $request) {
        $username = $request->query('username'); // Получаем параметр запроса 'username'

        if (!$username) {
            return response()->json(['error' => 'Username is required'], 400);
        }

        $user = User::where('username', 'like', "%{$username}%")->get();

        if ($user->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json($user)->setStatusCode(200);
    }

    // Поиск файла по названию (у пользователя)
    public function fileSearch(FileSearchRequest $request, $id) {
        $name = $request->name; // Получаем параметр запроса 'name'

        if (!$name) {
            return response()->json(['error' => 'Name is required'], 400);
        }

        $files = File::where('user_id', $id)->where('name', $name)->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => 'No files found'], 404);
        }

        return response()->json($files)->setStatusCode(200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AccessRight;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    // Добавление права доступа
    public function allow(Request $request, $id) {
        $userId = $request->user_id;

        // Проверяем, что пользователь указал user_id
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 422);
        }

        // Ищем файл по ID
        $file = File::find($id);

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Проверяем, является ли текущий пользователь владельцем файла
        if ($file->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not the owner of this file'], 403);
        }

        // Добавляем доступ
        AccessRight::create([
            'file_id' => $file->id,
            'user_id' => $userId,
        ]);

        return response()->json([
            'message' => 'Доступ успешно предоставлен',
            'user_id' => $userId,
            'file_id' => $file->id,
        ], 201);
    }

    // Удаление права доступа
    public function disallow(Request $request, $id) {
        $userId = $request->user_id;

        // Проверяем, что пользователь указал user_id
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 422);
        }

        // Ищем файл по ID
        $file = File::find($id);

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Проверяем, является ли текущий пользователь владельцем файла
        if ($file->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not the owner of this file'], 403);
        }

        // Удаляем доступ
        $access = AccessRight::where('file_id', $file->id)
            ->where('user_id', $userId)
            ->first();

        if (!$access) {
            return response()->json(['error' => 'Access not found'], 404);
        }

        $access->delete();

        return response()->json([
            'message' => 'Доступ успешно удалён',
            'user_id' => $userId,
            'file_id' => $file->id,
        ], 200);
    }
}

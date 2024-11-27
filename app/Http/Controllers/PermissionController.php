<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\AccessRight;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    // Добавление права доступа
    public function allow(PermissionRequest $request, $id) {
        $userId = $request->user_id;

        // Ищем файл по ID
        $file = File::find($id);

        if (!$file) {
            return response()->json('File not found')->setStatusCode(404);
        }

        // Проверяем, является ли текущий пользователь владельцем файла
        if ($file->user_id !== Auth::id()) {
            return response()->json('Вы не владелец файла')->setStatusCode(403);
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
        ])->setStatusCode(201);
    }

    // Удаление права доступа
    public function disallow(PermissionRequest $request, $id) {
        $userId = $request->user_id;

        // Ищем файл по ID
        $file = File::find($id);

        if (!$file) {
            return response()->json('File not found')->setStatusCode(404);
        }

        // Проверяем, является ли текущий пользователь владельцем файла
        if ($file->user_id !== Auth::id()) {
            return response()->json('Вы не владелец файла.')->setStatusCode(403);
        }

        // Удаляем доступ
        $access = AccessRight::where('file_id', $file->id)
            ->where('user_id', $userId)
            ->first();

        if (!$access) {
            return response()->json('Access not found')->setStatusCode(404);
        }

        $access->delete();

        return response()->json([
            'message' => 'Доступ успешно удалён',
            'user_id' => $userId,
            'file_id' => $file->id,
        ])->setStatusCode(200);
    }
}

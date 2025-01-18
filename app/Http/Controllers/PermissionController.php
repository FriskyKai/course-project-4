<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\AccessRight;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    // Просмотр данных прав доступа
    public function permsAllowed() {
        $user = User::where('id', Auth::user()->id)->first();

        $rights = AccessRight::where('owner', $user->username)->get();

        if ($rights->isEmpty()) {
            return response()->json('Вы ещё не предоставляли права доступа к файлам')->setStatusCode(404);
        }

        return response()->json([
            'perms' => PermissionResource::collection($rights),
        ])->setStatusCode(200);
    }

    // Просмотр полученных прав доступа
    public function permsReceived() {
        $rights = AccessRight::where('user_id', Auth::user()->id)->get();

        if ($rights->isEmpty()) {
            return response()->json('Вы ещё не получали права доступа к файлам')->setStatusCode(404);
        }

        return response()->json([
            'perms' => PermissionResource::collection($rights),
        ])->setStatusCode(200);
    }

    // Добавление права доступа
    public function allow(PermissionRequest $request, $id) {
        $userId = User::where('username', $request->username)->first()->id;

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
        $userId = User::where('username', $request->username)->first()->id;

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

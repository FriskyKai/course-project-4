<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(UserResource::collection($users))->setStatusCode(200);
    }

    public function show($user_id)
    {
        $user = User::where('id', $user_id)->firstOrFail();

        if (empty($user)) {
            throw new ApiException('Not Found', 404);
        }

        $files = File::where('user_id', $user->id)->get();

        return response()->json([
            'user' => new UserResource($user),
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if (empty($user->id)) {
            throw new ApiException('Not Found', 404);
        }

        $user->update($request->validated());

        $files = File::where('user_id', $user->id)->get();

        return response()->json([
            'user' => new UserResource($user),
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    public function destroy(User $user)
    {
        if (empty($user)) {
            throw new ApiException('Not Found', 404);
        }

        DB::beginTransaction();

        try {
            // Удаляем связанные данные
            $user->files()->delete();
            $user->accessRights()->delete();

            // Удаляем пользователя
            $user->delete();

            DB::commit();

            return response()->json('Пользователь удалён успешно.')->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json('Ошибка удаления пользователя: ' . $e->getMessage(), 500);
        }
    }
}

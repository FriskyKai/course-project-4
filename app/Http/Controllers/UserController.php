<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(UserResource::collection($users))->setStatusCode(200);
    }

    public function show(User $user)
    {
        if (empty($user->id)) {
            throw new ApiException('Not Found ', 404);
        }

        return response()->json(new UserResource($user))->setStatusCode(200);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if (empty($user->id)) {
            throw new ApiException('Not Found ', 404);
        }

        $user->update($request->validated());

        return response()->json(new UserResource($user))->setStatusCode(200);
    }

    public function destroy($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json('Пользователь не найден')->setStatusCode(404, 'Not found');
        }

        User::destroy($user_id);

        return response()->json(['message' => 'Пользователь успешно удалён.'])->setStatusCode(204);
    }
}

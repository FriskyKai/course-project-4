<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Маршруты для Регистрации, Авторизации и Выхода
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Маршруты для Пользователей
Route::middleware(['auth:api', CheckRole::class . ':admin'])->apiResource('users', UserController::class)->except('show');
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:api');

// Маршруты для Профиля
Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

// Маршруты для Файлов
Route::middleware('auth:api')->group(function () {
    // Загрузка
    Route::post('/files', [FileController::class, 'store']);

    // Просмотр всех (Админ)
    Route::get('/files', [FileController::class, 'index'])->middleware([CheckRole::class . ':admin']);
    // Просмотр своих
    Route::get('/files/disk', [FileController::class, 'disk']);
    // Просмотр разрешённых
    Route::get('/files/shared', [FileController::class, 'shared']);
    // Просмотр у пользователя (Админ)
    Route::get('/users/{id}/files', [FileController::class, 'show'])->middleware([CheckRole::class . ':admin']);

    // Скачивание
    Route::get('/files/{id}', [FileController::class, 'download']);
    // Редактирование
    Route::post('/files/{id}', [FileController::class, 'update']);
    // Удаление
    Route::delete('/files/{id}', [FileController::class, 'destroy']);
});

// Маршруты для Прав доступа
Route::middleware('auth:api')->group(function () {
    // Добавление прав доступа
    Route::post('/files/{id}/accesses', [PermissionController::class, 'allow']);
    // Удаление прав доступа
    Route::delete('/files/{id}/accesses', [PermissionController::class, 'disallow']);
});

// Маршруты для Поиска
Route::middleware('auth:api')->group(function () {
    // Поиск пользователя по имени
    Route::post('/search/user', [SearchController::class, 'userSearch']);
    // Поиск файла у пользователя по названию
    Route::post('/search/{id}/file', [SearchController::class, 'fileSearch']);
});

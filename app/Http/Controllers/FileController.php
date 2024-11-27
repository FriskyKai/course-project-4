<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\FileStoreRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    // Загрузка файла
    public function store(FileStoreRequest $request) {
        if (!$request->hasFile('file')) {
            return response()->json(['Файл не был передан!'])->setStatusCode(400);
        }

        // Сохранение файла в файловое хранилище
        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->store('files', 'public');

        // Получение метаданных файла
        $fileName = $uploadedFile->getClientOriginalName();
        $fileExtension = $uploadedFile->getClientOriginalExtension();
        $fileSize = $uploadedFile->getSize();

        // Создание записи в БД
        $file = new File();
        $file->name = $fileName;
        $file->extension = $fileExtension;
        $file->size = $fileSize;
        $file->path = '/storage/' . $filePath;
        $file->user_id = $request->user()->id; // Получаем ID пользователя из запроса
        $file->save();

        return response()->json([
            'message' => 'Файл успешно загружен',
            'file' => new FileResource($file),
        ])->setStatusCode(201);
    }

    // Скачивание файла
    public function download($file_id) {
        $file = File::find($file_id);

        if (!$file) {
            return response()->json(['message' => 'Файл не найден.'])->setStatusCode(404);
        }

        // Относительный путь
        $relativePath = str_replace('/storage/', '', $file->path);

        // Проверка наличия файла в хранилище
        if (!Storage::disk('public')->exists($relativePath)) {
            return response()->json('Файл отсутствует в хранилище.')->setStatusCode(404);
        }

        // Возвращаем файл пользователю
        return response()->download(
            public_path($file->path),
            $file->name
        );
    }

    // Просмотр всех файлов
    public function index() {
        $files = File::all();

        return response()->json([
            'files' => FileResource::collection($files),
        ])->setStatusCode(404);
    }

    // Просмотр файлов пользователя
    public function show($user_id) {
        $files = File::where('user_id', $user_id)->get();

        if ($files->isEmpty()) {
            return response()->json('Файлы пользователя не найдены.')->setStatusCode(404);
        }

        return response()->json([
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    // Просмотр своих файлов
    public function disk() {
        $files = File::where('user_id', Auth::user()->id)->get();

        if ($files->isEmpty()) {
            return response()->json('У вас ещё нет загруженных файлов.')->setStatusCode(404);
        }

        return response()->json([
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    // Просмотр разрешённых файлов
    public function shared() {
        $user_id = Auth::user()->id;

        // Получаем разрешённые файлы через соединение таблиц
        $files = File::join('access_rights', 'files.id', '=', 'access_rights.file_id')
            ->where('access_rights.user_id', $user_id)
            ->get(['files.*']); // Выбираем все столбцы из таблицы `files`

        // Проверяем, есть ли доступные файлы
        if ($files->isEmpty()) {
            return response()->json('Нет доступных файлов.')->setStatusCode(404);
        }

        return response()->json([
            'files' => FileResource::collection($files),
        ])->setStatusCode(200);
    }

    // Редактирование
    public function update(Request $request, $file_id) {
        // Поиск файла в БД
        $file = File::find($file_id);

        if (!$file) {
            return response()->json([
                'message' => 'Файл не найден.',
            ])->setStatusCode(404);
        }

        // Обновление имени файла
        $file->name = $request->name;
        $file->save();

        return response()->json([
            'message' => 'Имя файла успешно обновлено.',
            'file' => $file,
        ])->setStatusCode(200);
    }

    // Удаление
    public function destroy($file_id) {
        // Поиск файла в базе данных
        $file = File::find($file_id);

        if (!$file) {
            return response()->json([
                'message' => 'Файл не найден.',
            ], 404);
        }

        // Удаление файла из хранилища
        $relativePath = str_replace('/storage/', '', $file->path);

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        // Удаление записи из базы данных
        $file->delete();

        return response()->json([
            'message' => 'Файл успешно удалён.',
        ])->setStatusCode(200);
    }
}

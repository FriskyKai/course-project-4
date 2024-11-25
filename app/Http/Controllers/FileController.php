<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    // Загрузка файла
    public function store(Request $request) {
        if (!$request->hasFile('file')) {
            return response()->json(['Файл не был передан!',], 400);
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
        ], 201);
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
            return response()->json(['message' => 'Файл отсутствует в хранилище.'])->setStatusCode(404);
        }

        // Возвращаем файл пользователю
        return response()->download(
            public_path($file->path),
            $file->name
        );
    }

    // Просмотр всех файлов
    public function index() {

    }

    // Просмотр файлов пользователя
    public function show() {

    }

    // Просмотр своих файлов
    public function disk() {

    }

    // Просмотр разрешённых файлов
    public function shared() {

    }

    // Редактирование
    public function update(Request $request) {

    }

    // Удаление
    public function destroy(Request $request) {

    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    // Загрузка файла
    public function store(Request $request) {

    }

    // Скачивание файла
    public function download($file_id) {

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

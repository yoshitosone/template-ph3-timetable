<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ここに書いたルートは、URL の頭に /api が付く（/user なら /api/user）。
// W5 から、画面（React）に JSON を返すルートをここに足していく。

// php artisan install:api が入れたルート。ログインの仕組み（Sanctum）と一緒に W9 で使う。今週は触らない
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

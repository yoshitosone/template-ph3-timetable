<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\EntryApprovalController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/timetable', [TimetableController::class, 'index']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
// auth ミドルウェアは、ログインしていない人を名前が login のルートに飛ばす
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// ここから下は、ログインしていないと /login に飛ぶ
Route::middleware('auth')->group(function () {
    Route::get('/entries', [TimetableEntryController::class, 'index']);
    Route::get('/entries/create', [TimetableEntryController::class, 'create']);
    Route::post('/entries', [TimetableEntryController::class, 'store']);
    Route::delete('/entries/{id}', [TimetableEntryController::class, 'destroy']);

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);

    Route::post('/courses/{course}/favorite', [FavoriteController::class, 'store']);
    Route::delete('/courses/{course}/favorite', [FavoriteController::class, 'destroy']);
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // ここから下は、管理者でなければ 403 で止まる
    Route::middleware('can:decide-enrollments')->group(function () {
        Route::get('/admin/entries', [EntryApprovalController::class, 'index']);
        Route::post('/admin/entries/{entry}/approve', [EntryApprovalController::class, 'approve']);
        Route::post('/admin/entries/{entry}/reject', [EntryApprovalController::class, 'reject']);
        // 授業のお知らせを出す（教務課だけ）
        Route::post('/admin/courses/{course}/announcements', [AnnouncementController::class, 'store']);
    });
});

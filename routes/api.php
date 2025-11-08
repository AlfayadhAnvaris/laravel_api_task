<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentication
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/check', [AuthController::class, 'check']);
});

//Post
Route::get('/posts', [PostController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/posts/{id}', [PostController::class, 'show'])->middleware(['auth:sanctum']);
Route::post('/create/posts', [PostController::class, 'create'])->middleware(['auth:sanctum']);
Route::patch('edit/posts/{id}', [PostController::class, 'edit'])->middleware('pemilik-postingan');
Route::patch('delete/posts/{id}', [PostController::class, 'destroy'])->middleware('pemilik-postingan');

//Comments
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth:sanctum']);
Route::patch('/comments/{id}', [CommentController::class, 'save'])->middleware(['auth:sanctum', 'pemilik-komentar']);
Route::delete('/comments/{id}', [CommentController::class, 'destroy']) ->middleware(['auth:sanctum', 'pemilik-komentar']);









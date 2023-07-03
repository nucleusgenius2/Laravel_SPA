<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public api
Route::get('/connect', function () {
    return true;
});

//Route::get('/post-list/', [PostController::class, 'getPostList']);
Route::get('/posts', [PostController::class, 'getPostList']);
Route::get('/posts/{id}', [PostController::class, 'getPostSingle']);
Route::post('login', [UserController::class, 'loginUser']);
Route::post('registration', [UserController::class, 'registrationUser']);


//user api
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/authorization', [UserController::class, 'checkStatusUser']);
    Route::get('/logout/', [UserController::class, 'logoutUser']);

    Route::get('/profile', [ProfileController::class, 'profileInfo']);
    Route::patch('/profile', [ProfileController::class, 'profileUpdate']);
});

//admin api
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/posts/', [AdminController::class, 'createPost']);
    Route::patch('/posts/', [AdminController::class, 'updatePost']);
    Route::delete('/posts/{id}', [AdminController::class, 'deletePost']);
    //Route::post('/admin/create', [AdminController::class, 'createPost']);
    //Route::post('/admin/update', [AdminController::class, 'updatePost']);
    //Route::delete('/admin/delete/{id}', [AdminController::class, 'deletePost']);
});





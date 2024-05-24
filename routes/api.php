<?php


use App\Http\Controllers\ResetPasswordController;
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


Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::post('login', [UserController::class, 'login']);
Route::post('registration', [UserController::class, 'registration']);

Route::post('/reset_password', [ResetPasswordController::class, 'resetEmailMessage']);
Route::patch('/reset_password', [ResetPasswordController::class, 'reset']);

//user api
Route::middleware(['auth:sanctum', 'user_active'])->group(function () {
    Route::get('/authorization', [UserController::class, 'checkStatusUser']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::patch('/profile', [ProfileController::class, 'update']);
});

//admin api
Route::middleware(['auth:sanctum','user_active', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('/posts', [AdminController::class, 'create']);
    Route::patch('/posts', [AdminController::class, 'update']);
    Route::delete('/posts/{id}', [AdminController::class, 'destroy']);
});





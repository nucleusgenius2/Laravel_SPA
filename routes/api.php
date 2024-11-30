<?php


use App\Http\Controllers\MapsController;
use App\Http\Controllers\ModController;
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

    Route::get('/map/{name}', [MapsController::class, 'hasMap']);
    Route::get('/maps', [MapsController::class, 'index']);
    Route::get('/maps/downland', [MapsController::class, 'downlandMap']);

    Route::get('/mods', [ModController::class, 'index']);
    Route::get('/mods/downland', [ModController::class, 'downlandMod']);
});

//admin api
Route::middleware(['auth:sanctum','user_active', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('/posts', [AdminController::class, 'store']);
    Route::patch('/posts', [AdminController::class, 'update']);
    Route::delete('/posts/{id}', [AdminController::class, 'destroy']);

    Route::delete('/mods/{id}', [ModController::class, 'destroy']);
    Route::post('/mods', [ModController::class, 'store']);

    Route::delete('/maps/{id}', [MapsController::class, 'destroy']);
    Route::post('/maps', [MapsController::class, 'store']);
});





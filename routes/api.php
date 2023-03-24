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
Route::get('/news/{total}/{id}', [PostController::class, 'getPostListOrSinglePost']);
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
    Route::post('/admin/save', [AdminController::class, 'saveAndUpdatePost']);
    Route::delete('/admin/delete/{id}', [AdminController::class, 'deletePost']);
});






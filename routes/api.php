<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum', 'role:admin')->get('/admin', function (Request $request) {
    return response()->json(['message'=> 'Welcome Admin !']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->delete('/admin/users/{id}', [AuthController::class, 'deleteUser']);

Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/users', [AuthController::class, 'getAllUsers']);

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

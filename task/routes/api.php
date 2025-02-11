<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;



Route::get('test', function () {
    return response()->json(['message' => 'Hello from Laravel API!']);
});

Route::middleware([EnsureFrontendRequestsAreStateful::class])->get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/tasks', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/list', [TodoController::class, 'get']);
    Route::put('', [TodoController::class, 'create']);
    Route::post('', [TodoController::class, 'edit']);
    Route::delete('{id}', [TodoController::class, 'delete']);

});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

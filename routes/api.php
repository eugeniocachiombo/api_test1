<?php

use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("/v1")->group(function () {
    Route::get("/tasks/status/{status}", [TaskController::class, "filterByStatus"]);
    Route::get("/tasks/user/{user_id}", [TaskController::class, "filterByUser"]);
    Route::apiResource("/tasks", TaskController::class);
});

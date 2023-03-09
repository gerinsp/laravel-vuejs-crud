<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/items', App\Http\Controllers\ItemsController::class);

Route::put('/items-update/${id}', [App\Http\Controllers\ItemsController::class, 'updateItem']);

Route::get('/get-type', [App\Http\Controllers\ItemsController::class, 'getType']);

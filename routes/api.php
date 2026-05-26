<?php

use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 検索用APIルートを追記
Route::middleware('web')->group(function () {
    Route::get('/menus/search-similar', [MenuController::class, 'searchSimilar']);
});

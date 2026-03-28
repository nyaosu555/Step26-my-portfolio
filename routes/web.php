<?php

use App\Http\Controllers\MealRecordController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 献立履歴一覧表示
    Route::get('/meal-records', [MealRecordController::class, 'index'])->name('meal_records.index');

    // メニュー一覧表示
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');

    // メニュー登録画面表示
    // Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');

    // メニューデータ登録
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');

    // メニューの削除
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
});

require __DIR__.'/auth.php';

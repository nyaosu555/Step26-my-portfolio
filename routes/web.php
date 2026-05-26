<?php

use App\Http\Controllers\MealRecordController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlotController;
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
    Route::get('/menus', [MenuController::class, 'index'])->middleware('auth')->name('menus.index');

    // メニューデータ登録
    Route::post('/menus', [MenuController::class, 'store'])->middleware('auth')->name('menus.store');

    // メニューの編集画面を表示
    Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->middleware('auth')->name('menus.edit');

    // メニューの更新
    Route::patch('menus/{menu}', [MenuController::class, 'update'])->middleware('auth')->name('menus.update');

    // メニューの削除
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('auth')->name('menus.destroy');

    // スロット画面の表示
    Route::get('/', [SlotController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('index');

    // 献立の保存登録
    Route::post('/meal-records', [MealRecordController::class, 'store'])->middleware('auth');

    // 保存した献立の削除
    Route::delete('/meal-records/select-delete', [MealRecordController::class, 'selectDestroy'])->middleware('auth')->name('meal_records.select_destroy');
});

require __DIR__.'/auth.php';


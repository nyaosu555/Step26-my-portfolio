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

    // メニュー登録画面表示
    // Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');

    // メニューデータ登録
    // Route::post('/menus', [MenuController::class, 'store'])->middleware('can:admin')->name('menus.store');
    Route::post('/menus', [MenuController::class, 'store'])->middleware('auth')->name('menus.store');

    // メニューの削除
    // Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('can:admin')->name('menus.destroy');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('auth')->name('menus.destroy');

    // スロット画面の表示
    Route::get('/', [SlotController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('index');

    // 献立の保存登録
    Route::post('/meal-records', [MealRecordController::class, 'store'])->middleware('auth');

    // 保存した献立の削除
    Route::post('/meal-records/select-delete', [MealRecordController::class, 'selectDestroy'])->middleware('auth');
});

require __DIR__.'/auth.php';

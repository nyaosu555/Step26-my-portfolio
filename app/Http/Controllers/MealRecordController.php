<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealRecordController extends Controller
{
    //献立の一覧表示をする
    public function index() {
        // 1. ログイン中のユーザーの献立だけを取得する
        // 2. その際、紐づいている明細（MealRecordItems）も一緒に読み込む
        $mealRecords = Auth::user()->mealRecords()
            ->with('mealRecodItems.menu')
            ->orderBy('date', 'desc')
            ->get();

        // 3. ビュー（画面）にデータを渡す
        return view('meal_records.index', compact('mealRecords'));
    }
}

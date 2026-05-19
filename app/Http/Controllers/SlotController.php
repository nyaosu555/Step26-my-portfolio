<?php

namespace App\Http\Controllers;

use App\Enums\MenuType;
use Illuminate\Support\Facades\Auth;

class SlotController extends Controller
{
    // スロット画面を表示
    public function index() {
        // ログインユーザーのメニューを一括取得
        $menus = Auth::user()->menus;

        // 各料理タイプの登録件数を集計
        $counts = [
            'main' => $menus->where('type_id', MenuType::Main->value)->count(),
            'side_a' =>  $menus->where('type_id', MenuType::SideA->value)->count(),
            'side_b' =>  $menus->where('type_id', MenuType::SideB->value)->count(),
        ];

        // 全てのタイプが3つ以上登録されているか（スロット可能か）を判定
        $isReady = ($counts['main'] >= 3 && $counts['side_a'] >= 3 && $counts['side_b'] >= 3);

        return view('slot.index', compact('menus', 'counts', 'isReady'));
    }
}

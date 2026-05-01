<?php

namespace App\Http\Controllers;

use App\Enums\MenuType;
use App\Models\Menu;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    //
    public function index() {
        // 1. ログインユーザーのメニューを「メイン」「副菜A」「副菜B」に分けて取得する
        $menus = Menu::where('user_id', auth()->id())->get();

        // 2. 各料理タイプの登録件数を集計
        // $counts = [
        //     'main' => $menus->where('type_id', 1)->count(),
        //     'side_a' =>  $menus->where('type_id', 2)->count(),
        //     'side_b' =>  $menus->where('type_id', 3)->count(),
        // ];
        $counts = [
            'main' => $menus->where('type_id', MenuType::Main->value)->count(),
            'side_a' =>  $menus->where('type_id', MenuType::SideA->value)->count(),
            'side_b' =>  $menus->where('type_id', MenuType::SideB->value)->count(),
        ];

        // 3. 全てのタイプが3つ以上登録されているかを判定
        $isReady = ($counts['main'] >= 3 && $counts['side_a'] >= 3 && $counts['side_b'] >= 3);



        return view('slot.index', compact('menus', 'counts', 'isReady'));
    }
}

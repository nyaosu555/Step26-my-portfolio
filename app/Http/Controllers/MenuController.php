<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{

    //
    public function create() {
        return view('menus.create');
    }

    //
    public function store(Request $request) {
        // 1.バリデーション（入力チェック）
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:types,id',
            'image_path' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'recipe_url' => 'nullable|url|max:255',
            'memo' => 'nullable|string|max:1000',
        ], [
            'image_path.max' => '画像サイズは2M以下にしてください。',
            'image_path.image' => '選択されたファイルは画像ではありません。',
            'image_path.mimes' => 'png, jpg, jpeg, gif形式の画像を選択してください。',
        ]);

        // 2.写真の保存処理
        $imagePath = null;
        if($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('menu_images', 'public');
        }

        // 3.データベースに保存
        Menu::create([
            'user_id' => Auth::id(),
            'name' => $request->menu_name,
            'type_id' => $request->type_id,
            'image_path' => $imagePath,
            'recipe_url' => $request->recipe_url,
            'memo' => $request->memo,
        ]);

        // return redirect()->route('menus.index')->with('success', 'メニューを登録しました。');
        return redirect()->route('menus.index')->with([
            'message' => 'メニューを登録しました',
            'type' => 'success',
            ]);
    }

    public function index() {
        // 現在ログインしているユーザー情報を取得
        $user = Auth::user();

        // 権限に応じて取得するメニューを分岐
        if($user->role === 'admin') {
            // 管理者の場合：すべてのユーザーのメニューを取得
            $menus = Menu::with(['user', 'type'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // 一般ユーザーの場合：自分のメニューのみ取得
            $menus = Menu::where('user_id', $user->id)
                        ->with('type')
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        // // 1. ログインユーザーのメニューのみを取得
        // $menus = Menu::where('user_id', auth()->id())
        //             ->orderBy('created_at', 'desc')
        //             ->get();

        // 2. 登録フォーム用セレクトボックス用データ
        $types = Type::all();

        return view('menus.index', compact('menus', 'types'));
    }

    public function destroy(Menu $menu) {
        // 現在ログインしているユーザー
        $user = Auth::user();

        // 認可チェック：「メニューの所有者ではない」かつ「管理者でもない」場合
        if($menu->user_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('menus.index')->with([
                'message' => '削除する権限がありません',
                'type' => 'danger',
            ]);
        }

        // // 1. ログインユーザーとメニューの所有者が一致しているかをチェック
        // if($menu->user_id !== auth()->id()) {
        //     return redirect()->route('menus.index')->with([
        //         'message' => '削除する権限がありません',
        //         'type' => 'danger',
        //     ]);
        // }

        // 2. サーバー上の画像を削除
        if($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }

        // 3. データベースから削除
        $menu->delete();

        // 4. 一覧画面に戻す
        return redirect()->route('menus.index')->with([
            'message' => 'メニューを削除しました。',
            'type' => 'danger',
        ]);
    }
}

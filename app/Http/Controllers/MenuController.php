<?php

namespace App\Http\Controllers;

use App\Enums\MenuType;
use App\Http\Requests\StoreMenuRequest;
use App\Models\Menu;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class MenuController extends Controller
{

    //
    public function create() {
        return view('menus.create');
    }

    //
    // public function store(Request $request) {
    public function store(StoreMenuRequest $request) {


        // 【重要】ここにデータがきた時点で、すでにバリデーションは「合格」している。
        // 不合格の場合は、このメソッドが動く前に画面へ戻される。

        // 1. 検査済みのデータだけを取得
        $validated = $request->validated();

        // 1.バリデーション（入力チェック）→FormRequestで管理するためコメントアウト
        // $request->validate([
        //     'menu_name' => 'required|string|max:255',
        //     'type_id' => [
        //         'required',
        //         'integer',
        //         'exists:types,id',
        //         new Enum(MenuType::class) // Enumで定義した値(1,2,3)以外は弾くことで厳しくチェック
        //     ],
        //     'image_path' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
        //     'recipe_url' => 'nullable|url|max:255',
        //     'memo' => 'nullable|string|max:1000',
        // ], [
        //     'image_path.max' => '画像サイズは2M以下にしてください。',
        //     'image_path.image' => '選択されたファイルは画像ではありません。',
        //     'image_path.mimes' => 'png, jpg, jpeg, gif形式の画像を選択してください。',
        // ]);

        try {

            // 2.写真の保存処理
            $imagePath = null;
            if($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('menu_images', 'public');
                }

                // 3. データベースに保存
                // Menu::create([
                    //     'user_id'       => Auth::id(),
                    //     'name'          => $validated['menu_name'],
                    //     'type_id'       => $validated['type_id'],
                    //     'image_path'    => $imagePath,
                    //     'recipe_url'    => $validated['recipe_url'] ?? null,
                    //     'memo'          => $validated['memo'] ?? null,
                    // ]);

                    Auth::user()->menus()->create([
                        'name'          => $validated['menu_name'],
                        'type_id'       => $validated['type_id'],
                        'image_path'    => $imagePath,
                        'recipe_url'    => $validated['recipe_url'] ?? null,
                        'memo'          => $validated['memo'] ?? null,
                        ]);

                        // 3.データベースに保存
                        // Menu::create([
                            //     'user_id' => Auth::id(),
                            //     'name' => $request->menu_name,
                            //     'type_id' => $request->type_id,
                            //     'image_path' => $imagePath,
                            //     'recipe_url' => $request->recipe_url,
                            //     'memo' => $request->memo,
                            // ]);

                            // return redirect()->route('menus.index')->with('success', 'メニューを登録しました。');
                            return redirect()->route('menus.index')->with([
                                'message' => 'メニューを登録しました',
                                'type' => 'success',
                                ]);
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            Log::error('メニュー登録失敗', [
                'user_id'       =>  Auth::id(),
                'input'         =>  $request->except('image_path'),
                'error'         =>  $e->getMessage(),
                'trace'         =>  $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with([
                'message'   =>  '登録中にエラーが発生しました。時間をおいて再度お試しください。',
                'type'      =>  'danger',
            ]);
        }
    }

    public function index() {
        // 現在ログインしているユーザー情報を取得
        $user = Auth::user();

        // 権限に応じて取得するメニューを分岐
        if($user->role === 'admin') {
            // 管理者の場合：すべてのユーザーのメニューを取得
            $menus = Menu::with(['user', 'type'])
                        ->orderBy('created_at', 'desc')
                        // ->get();
                        ->paginate(10);
        } else {
            // 一般ユーザーの場合：自分のメニューのみ取得
            // $menus = Menu::where('user_id', $user->id)
            //             ->with('type')
            //             ->orderBy('created_at', 'desc')
            //             // ->get();
            //              ->paginate(10);
            $menus = $user->menus()
                        ->with('type')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
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

        try {
            // 3. データベースから削除
            $menu->delete();


            // 2. サーバー上の画像を削除
            if($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }


            // 4. 一覧画面に戻す
            return redirect()->route('menus.index')->with([
                'message' => 'メニューを削除しました。',
                'type' => 'success',
                ]);
        } catch (\Throwable $e) {
            Log::error('メニュー削除失敗', [
                'user_id'   =>  Auth::id(),
                'menu_id'   =>  $menu->id,
                'error'     =>  $e->getMessage(),
                'trace'     =>  $e->getTraceAsString(),
            ]);

            return redirect()->route('menus.index')->with([
                'message'   =>  '削除中にエラーが発生しました。時間をおいて再度お試しください。',
                'type'      => 'danger',
            ]);
        }
    }
}

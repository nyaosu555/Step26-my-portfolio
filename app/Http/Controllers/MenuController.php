<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Models\Menu;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizationException;

class MenuController extends Controller
{
    use AuthorizesRequests;

    // メニュー登録画面を表示
    public function create() {
        return view('menus.create');
    }

    // 新しいメニューを登録
    public function store(StoreMenuRequest $request) {
        // バリデーション済みのデータを取得（tryの外でOK）
        $validated = $request->validated();

        try {
            // 写真の保存処理
            $imagePath = null;
            if($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('menu_images', 'public');
            }

            // ログインユーザーに紐づけてメニューをデータベースに保存
            Auth::user()->menus()->create([
                'name'          => $validated['menu_name'],
                'type_id'       => $validated['type_id'],
                'image_path'    => $imagePath,
                'recipe_url'    => $validated['recipe_url'] ?? null,
                'memo'          => $validated['memo'] ?? null,
            ]);

            return redirect()->route('menus.index')->with([
                'message' => 'メニューを登録しました',
                'type' => 'success',
            ]);

        } catch (\Throwable $e) {
            // DB保存失敗時はアップロードした画像を削除してロールバック
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

    // 登録メニュー一覧の表示
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
            $menus = $user->menus()
                    ->with('type')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        }

        // 登録フォーム用セレクトボックス用データ
        $types = Type::all();

        return view('menus.index', compact('menus', 'types'));
    }

    // メニューの削除処理
    public function destroy(Menu $menu) {
        try {
            // Laravelが自動的に MenuPolicy の delete メソッドを呼び出してチェックする
            // 権限がない場合は自動的に「catch」に入る
            $this->authorize('delete', $menu);

            // データベースから削除
            $menu->delete();


            // サーバー上の画像を削除
            if($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }


            // 一覧画面に戻す
            return redirect()->route('menus.index')->with([
                'message' => 'メニューを削除しました。',
                'type' => 'success',
            ]);

        } catch (AuthorizationException $e) {
            // 👈 3. 権限エラー（403）が発生した場合はここへジャンプします！
            Log::warning("不審なアクセス: ユーザーID " . auth()->id() . " がメニューID " . $menu->id . " を削除しようとしました。");

            return redirect()->route('menus.index')->with([
                'message' => '削除する権限がありません。',
                'type' => 'danger', // 赤色のFlashメッセージにする場合
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

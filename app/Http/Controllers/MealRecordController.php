<?php

namespace App\Http\Controllers;

use App\Enums\MenuType;
use App\Http\Requests\DeleteMealRecordRequest;
use App\Http\Requests\StoreMealRecordRequest;
use App\Models\MealRecord;
use App\Models\MealRecordItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MealRecordController extends Controller
{
    //献立の一覧表示をする
    public function index() {
        // ログインユーザーの献立だけを取得する
        // その際、主菜・副菜A・副菜Bをそれぞれの窓口（作成したリレーション）経由で一括読み込みする
        $mealRecords = Auth::user()->mealRecords()
            ->with([
                'mainDish.menu',
                'sideDishA.menu',
                'sideDishB.menu',
            ])
            ->orderBy('date', 'desc')
            ->paginate(10);

        // ビューにデータを渡す
        return view('meal_records.index', compact('mealRecords'));
    }

    // 献立登録処理
    public function store(StoreMealRecordRequest $request) {
        // バリデーション済みのデータを取得（tryの外でOK）
        $validated = $request->validated();

        try {
            // 重複チェック
            $today = now()->format('Y-m-d');
            $exists = MealRecord::where('user_id', Auth::id())
                                ->where('date', $today)
                                ->exists();

            if($exists) {
                // ログを残す】（ただし、例外やエラーではなく「警告(Warning)」として）
                Log::warning('献立の重複保存が試行されました', [
                    'user_id' => Auth::id(),
                    'date'    => $today,
                    'input'   => $request->all(),
                ]);

                // 【ユーザーに409を返す】（ここでこのstoreメソッドの処理は「終了」）
                return response()->json([
                    'message' => '本日の献立はすでに保存済みです。'
                ], 409);
            }

            // トランザクション（親子の保存をセットで行う）
            return DB::transaction(function() use ($validated) {
                // 2-1. 親（MealRecord）の作成
                $record = MealRecord::create([
                    'user_id'   => Auth::id(),
                    'date'      => now()->format('Y-m-d'),
                ]);

                // 子（MealRecordItem）の作成データを準備
                $items = [
                    ['type_id' => MenuType::Main->value, 'menu_id' => $validated['main_dish_id']],
                    ['type_id' => MenuType::SideA->value, 'menu_id' => $validated['sub_dish_a_id']],
                    ['type_id' => MenuType::SideB->value, 'menu_id' => $validated['sub_dish_b_id']],
                ];

                // 保存実行
                foreach($items as $item) {
                    MealRecordItem::create([
                        'meal_record_id'    =>  $record->id,
                        'menu_id'           =>  $item['menu_id'],
                        'type_id'           =>  $item['type_id'],
                    ]);
                }

                return response()->json(['message'=> '献立を保存しました！'], 200);
            });

        } catch(\Throwable $e) {
            // ログに詳細なコンテキスト（状況）を記録する
            Log::error('献立保存に失敗', [
                'user_id' =>    Auth::id(),
                'input' =>      $request->all(),
                'error' =>      $e->getMessage(),
                'trace' =>      $e->getTraceAsString(),
            ]);

            // ユーザーには内部構造を見せずに、丁寧なエラーメッセージを返す
            return response()->json([
                'message' =>    '保存に失敗しました。時間をおいて再度お試しください。'
            ], 500);
        }
    }

    // 保存した献立削除処理
    public function selectDestroy(DeleteMealRecordRequest $request) {
        // すでにバリデーション済みなのでtryの外に記述
        $validated = $request->validated();

        try {
            // 削除の実行（自分のデータ、かつ指定されたIDのみ）
            // $deletedには実際に削除した件数が返ってくる
            $deleted = MealRecord::where('user_id', Auth::id())
                                    ->whereIn('id', $validated['ids'])
                                    ->delete();

            session()->flash('message', "{$deleted}件の献立を削除しました。");
            session()->flash('type', 'success');

            // JSONレスポンス（JavaScript側の通知用）
            return response()->json([
                    'message' => "{$deleted}件の献立を削除しました。"
            ], 200);

        } catch (\Throwable $e) {

            Log::error('献立の一括削除に失敗', [
                'user_id'   =>      Auth::id(),
                'ids'       =>      $validated['ids'],
                'error'     =>      $e->getMessage(),
                'trace'     =>      $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => '削除処理中にエラーが発生しました。時間をおいて再度お試しください。'
            ], 500);
        }
    }
}

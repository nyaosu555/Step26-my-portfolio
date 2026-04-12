<?php

namespace App\Http\Controllers;

use App\Models\MealRecord;
use App\Models\MealRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    // 献立登録処理
    public function store(Request $request) {
        //     // ログにリクエスト内容を出力
        // Log::info('保存ボタンが押されました', $request->all());

        // return response()->json([
        //     'received_data' => $request->all()
        // ], 200);

        // 1. 同日に2件は献立を保存できないようにする
        $today = now()->format('Y-m-d');
        $exists = MealRecord::where('user_id', Auth::id())->where('date', $today)->exists();
        if($exists) {
            return response()->json([
                'message' => '本日の献立はすでに保存済みです。'
            ], 442);
        }

        // 2. バリデーション
        $validated = $request->validate([
        // exists:テーブル名,カラム名 という書き方にします
            'main_dish_id'  => 'required|exists:menus,id',
            'sub_dish_a_id' => 'required|exists:menus,id',
            'sub_dish_b_id' => 'required|exists:menus,id',
        ]);

        try {
            // 3. トランザクション（親子の保存をセットで行う）
            return DB::transaction(function () use ($validated) {
                // 3. 親（meal_records）の作成
                $record = MealRecord::create([
                    'user_id' => Auth::id(),
                    'date' => now()->format('Y-m-d'),
                ]);

                // 4. 子（meal_record_items）用のデータ（主菜・副菜A・副菜B）
                // ここで DB上のカラム名 'menu_id' と 'type_id' にマッピングします
                $items = [
                    [
                        'type_id' => 1, // 主菜
                        'menu_id' => $validated['main_dish_id']
                    ],
                    [
                        'type_id' => 2, // 副菜A
                        'menu_id' => $validated['sub_dish_a_id']
                    ],
                    [
                        'type_id' => 3, // 副菜B
                        'menu_id' => $validated['sub_dish_b_id']
                    ],
                ];

                // 5. まとめて保存
                foreach($items as $item) {
                    MealRecordItem::create([
                        'meal_record_id' => $record->id,
                        'menu_id' => $item['menu_id'],
                        'type_id' => $item['type_id'],
                    ]);
                }
                return response()->json(['message'=> '献立を保存しました！'], 200);
            });

        } catch (\Exception $e) {
            // エラー時はログに残すと調査が楽になります
            Log::error($e->getMessage());
            return response()->json(['message' => '保存に失敗しました'], 500);
        }


    }

    // 保存した献立削除処理
    public function selectDestroy(Request $request) {
            $ids = $request->input('ids');

            if(!$ids) {
                return response()->json([
                    'message' => 'IDがありません。'
                ], 400);
            }

            MealRecord::where('user_id', Auth::id())->whereIn('id', $ids)->delete();

            // return redirect()->route('meal_records.index')->with([
            //     'message' => count($request->ids) . '件の献立を削除しました。',
            //     'type' => 'danger',
            // ]);
            session()->flash('message', count($ids) . '件の献立を削除しました。');
            session()->flash('type', 'danger');

            return response()->json([
                'message' => '削除成功'
            ], 200);

    }
}

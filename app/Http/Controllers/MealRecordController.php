<?php

namespace App\Http\Controllers;

use App\Enums\MenuType;
use App\Http\Requests\DeleteMealRecordRequest;
use App\Http\Requests\StoreMealRecordRequest;
use App\Models\MealRecord;
use App\Models\MealRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MealRecordController extends Controller
{
    //献立の一覧表示をする
    // public function index() {
    //     // 1. ログイン中のユーザーの献立だけを取得する
    //     // 2. その際、紐づいている明細（MealRecordItems）も一緒に読み込む
    //     $mealRecords = Auth::user()->mealRecords()
    //         ->with('mealRecordItems.menu')
    //         ->orderBy('date', 'desc')
    //         // ->get();
    //         ->paginate(10);     //1ページ20件

    //     // 3. ビュー（画面）にデータを渡す
    //     return view('meal_records.index', compact('mealRecords'));
    // }
    public function index() {
        // 1. ログインユーザーの献立だけを取得する
        // 2. その際、主菜・副菜A・副菜Bをそれぞれの窓口（作成したリレーション）経由で一括読み込みする
        $mealRecords = Auth::user()->mealRecords()
            ->with([
                'mainDish.menu',
                'sideDishA.menu',
                'sideDishB.menu',
            ])
            ->orderBy('date', 'desc')
            ->paginate(10);

        // 3. ビューにデータを渡す
        return view('meal_records.index', compact('mealRecords'));
    }

    // 献立登録処理
    // public function store(Request $request) {
    public function store(StoreMealRecordRequest $request) {
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
            ], 409);
        }

        // 2. バリデーション済みのデータを取得
        $validated = $request->validated();

        // 2. バリデーション → StoreMealRecordRequestでバリデーションを管理するためコメントアウト
        // $userId = Auth::id();    //今ログインしている人のIDを取得

        // $validated = $request->validate([
        //     'main_dish_id' => [
        //         'required', Rule::exists('menus', 'id')
        //         ->where('user_id', $userId)->where('type_id', MenuType::Main->value)
        //     ],
        //     'sub_dish_a_id' => [
        //         'required', Rule::exists('menus', 'id')
        //         ->where('user_id', $userId)->where('type_id', MenuType::SideA->value)
        //     ],
        //     'sub_dish_b_id' => [
        //         'required', Rule::exists('menus', 'id')
        //         ->where('user_id', $userId)->where('type_id', MenuType::SideB->value)
        //     ], [
        //         'main_dish_id.exists' => '選択された主菜は無効です。',
        //         'main_dish_a_id.exists' => '選択された副菜Aは無効です。',
        //         'main_dish_b_id.exists' => '選択された副菜Bは無効です。',
        //     ]
        // ]);

        // $validated = $request->validate([
        // // exists:テーブル名,カラム名 という書き方にします
        //     'main_dish_id'  => 'required|exists:menus,id',
        //     'sub_dish_a_id' => 'required|exists:menus,id',
        //     'sub_dish_b_id' => 'required|exists:menus,id',
        // ]);

        try {
            return DB::transaction(function() use ($validated) {
                // 3. 親（MealRecord）の作成
                $record = MealRecord::create([
                    'user_id'   => Auth::id(),
                    'date'      => now()->format('Y-m-d'),
                ]);

                // 4. 子（MealRecordItem）の作成データを準備
                $items = [
                    ['type_id' => MenuType::Main->value, 'menu_id' => $validated['main_dish_id']],
                    ['type_id' => MenuType::SideA->value, 'menu_id' => $validated['sub_dish_a_id']],
                    ['type_id' => MenuType::SideB->value, 'menu_id' => $validated['sub_dish_b_id']],
                ];

                // 5. 保存実行
                foreach($items as $item) {
                    MealRecordItem::create([
                        'meal_record_id'    =>  $record->id,
                        'menu_id'           =>  $item['menu_id'],
                        'type_id'           =>  $item['type_id'],
                    ]);
                }

                return response()->json(['message'=> '献立を保存しました！'], 200);
            });
        } catch(\Exception $e) {
            Log::error("献立保存失敗: " . $e->getMessage());
            return response()->json(['message' => '保存に失敗しました'], 500);
        }

        // try {
        //     // 3. トランザクション（親子の保存をセットで行う）
        //     return DB::transaction(function () use ($validated) {
        //         // 3. 親（meal_records）の作成
        //         $record = MealRecord::create([
        //             'user_id' => Auth::id(),
        //             'date' => now()->format('Y-m-d'),
        //         ]);

        //         // 4. 子（meal_record_items）用のデータ（主菜・副菜A・副菜B）
        //         // ここで DB上のカラム名 'menu_id' と 'type_id' にマッピングします
        //         $items = [
        //             [
        //                 'type_id' => MenuType::Main->value, // 主菜
        //                 'menu_id' => $validated['main_dish_id']
        //             ],
        //             [
        //                 'type_id' => MenuType::SideA->value, // 副菜A
        //                 'menu_id' => $validated['sub_dish_a_id']
        //             ],
        //             [
        //                 'type_id' => MenuType::SideB->value, // 副菜B
        //                 'menu_id' => $validated['sub_dish_b_id']
        //             ],
        //         ];

        //         // 5. まとめて保存
        //         foreach($items as $item) {
        //             MealRecordItem::create([
        //                 'meal_record_id' => $record->id,
        //                 'menu_id' => $item['menu_id'],
        //                 'type_id' => $item['type_id'],
        //             ]);
        //         }
        //         return response()->json(['message'=> '献立を保存しました！'], 200);
        //     });

        // } catch (\Exception $e) {
        //     // エラー時はログに残すと調査が楽になります
        //     Log::error($e->getMessage());
        //     return response()->json(['message' => '保存に失敗しました'], 500);
        // }


    }

    // 保存した献立削除処理
    // public function selectDestroy(Request $request) {
    public function selectDestroy(DeleteMealRecordRequest $request) {
        // すでにバリデーション済み
        $validated = $request->validated();

        // // バリデーション（不正なデータが来るのを防ぐ）
        // $validated = $request->validate([
        //     'ids'   => 'required|array|min:1',      //idsは必須、配列形式、1つ以上
        //     'ids.*' => 'integer',                       //配列の中身はすべて数字
        // ]);

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


            // $ids = $request->input('ids');

            // バリデーションのおかげで↓のコードは不要
            // if(!$ids) {
            //     return response()->json([
            //         'message' => 'IDがありません。'
            //     ], 400);
            // }

            // MealRecord::where('user_id', Auth::id())->whereIn('id', $ids)->delete();

            // return redirect()->route('meal_records.index')->with([
            //     'message' => count($request->ids) . '件の献立を削除しました。',
            //     'type' => 'danger',
            // ]);
            // session()->flash('message', count($ids) . '件の献立を削除しました。');
            // session()->flash('type', 'danger');

            // return response()->json([
            //     'message' => '削除成功'
            // ], 200);

    }
}

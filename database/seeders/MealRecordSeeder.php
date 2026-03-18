<?php

namespace Database\Seeders;

use App\Models\MealRecord;
use App\Models\MealRecordItem;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. テスト用のユーザーを取得、いなければ作成
        $user = User::first() ?? User::factory()->create();

        // // 2. 親データ：献立の「枠」を作成
        // $record = MealRecord::create([
        //     'user_id' => $user->id,
        //     'date' => '2026-03-04',
        // ]);

        // // 3. 各タイプのメニューを1つずつ取得
        // $mainMenu = Menu::where('type_id', 1)->first();
        // $sideAMenu = Menu::where('type_id', 2)->first();
        // $sideBMenu = Menu::where('type_id', 3)->first();

        // // 4. 子データ：献立の「中身（明細）」を保存
        // // メイン
        // if($mainMenu) {
        //     MealRecordItem::create([
        //         'meal_record_id' => $record->id,    //2. で作ったデータのid
        //         'menu_id' => $mainMenu->id,
        //         'type_id' => $mainMenu->type_id,
        //     ]);
        // }
        // // 副菜A
        // if($sideAMenu) {
        //     MealRecordItem::create([
        //         'meal_record_id' => $record->id,    //2. で作ったデータのid
        //         'menu_id' => $sideAMenu->id,
        //         'type_id' => $sideAMenu->type_id,
        //     ]);
        // }
        // // 副菜B
        // if($sideBMenu) {
        //     MealRecordItem::create([
        //         'meal_record_id' => $record->id,    //2. で作ったデータのid
        //         'menu_id' => $sideBMenu->id,
        //         'type_id' => $sideBMenu->type_id,
        //     ]);
        // }

        // 2. 作成したい日付リスト
        $dates = [
            '2026-03-05',
            '2026-03-07',
            '2026-03-11',
        ];

        foreach($dates as $date) {
            // 3. 親データ（献立の枠）を作成
            $record = MealRecord::create([
                'user_id' => $user->id,
                'date' => $date,
            ]);

            for($typeId = 1; $typeId <= 3; $typeId++) {
                $menu = Menu::where('type_id', $typeId)->inRandomOrder()->first();

                if($menu) {
                    MealRecordItem::create([
                        'meal_record_id' => $record->id,
                        'menu_id' => $menu->id,
                        'type_id' => $typeId,

                    ]);
                }
            }
        }
    }
}

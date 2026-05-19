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
        // テスト用のユーザーを取得、いなければ作成
        $user = User::first() ?? User::factory()->create();

        // 作成したい日付リスト
        $dates = [
            '2026-03-05',
            '2026-03-07',
            '2026-03-11',
        ];

        foreach($dates as $date) {
            // 親データ（献立の枠）を作成
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

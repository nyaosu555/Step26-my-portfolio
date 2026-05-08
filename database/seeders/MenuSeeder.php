<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::where('email', 'test@example.com')->first();

        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => 'ハンバーグ',
        //     'type_id' => 1,
        //     'recipe_url' => 'https://recipe.example.com/hamburg',
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => 'ミートソーススパゲッティ',
        //     'type_id' => 1,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => '肉じゃが',
        //     'type_id' => 1,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => '無限ピーマン',
        //     'type_id' => 2,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => '彩り温野菜サラダ',
        //     'type_id' => 3,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => 'ほうれん草のお浸し',
        //     'type_id' => 2,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => '冷奴',
        //     'type_id' => 3,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => '湯豆腐',
        //     'type_id' => 3,
        // ]);
        // $user->menus()->create([
        //     'user_id' => $user->id,
        //     'name' => 'ポテトサラダ',
        //     'type_id' => 2,
        //     'recipe_url' => 'https://recipe.example.com/potatosalada',

        // ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => 'ハンバーグ',
            'type_id' => 1,
            'recipe_url' => 'https://recipe.example.com/hamburg',
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => 'ミートソーススパゲッティ',
            'type_id' => 1,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => '肉じゃが',
            'type_id' => 1,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => '無限ピーマン',
            'type_id' => 2,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => '彩り温野菜サラダ',
            'type_id' => 3,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => 'ほうれん草のお浸し',
            'type_id' => 2,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => '冷奴',
            'type_id' => 3,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => '湯豆腐',
            'type_id' => 3,
        ]);
        $user->menus()->create([
            'user_id' => $user->id,
            'name' => 'ポテトサラダ',
            'type_id' => 2,
            'recipe_url' => 'https://recipe.example.com/potatosalada',

        ]);
    }
}

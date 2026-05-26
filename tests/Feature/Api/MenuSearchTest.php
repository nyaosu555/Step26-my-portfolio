<?php

namespace Tests\Feature\Api;

use App\Models\Menu;
use App\Models\User;
use Database\Seeders\TypeSeeder; // 💡 シーダーのインポートを追加
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 各テストが実行される前の共通準備
     */
    protected function setUp(): void
    {
        parent::setUp();
        // メニュー登録に必須なマスターデータ（主菜・副菜など）を流し込む
        $this->seed(TypeSeeder::class);
    }

    /**
     * ユーザーは自分のメニューから類似するメニューを部分一致で検索できる
     */
    public function test_user_can_search_similar_menus_from_their_own_data(): void
    {
        $user = User::factory()->create();

        // ログインユーザーに紐づくメニュー（ヒットする想定）
        // シーダーが回っているので、type_id は Factory のデフォルト（または直書きの2）のままで自動的にエラーが消えます！
        $myMenu1 = Menu::factory()->create([
            'user_id' => $user->id,
            'name' => '肉じゃが'
        ]);
        $myMenu2 = Menu::factory()->create([
            'user_id' => $user->id,
            'name' => '豚肉の生姜焼き'
        ]);

        // ログインユーザーに紐づくが、キーワードが一致しないメニュー（ヒットしない想定）
        $myMenuUnmatched = Menu::factory()->create([
            'user_id' => $user->id,
            'name' => 'サバの塩焼き'
        ]);

        // 他のユーザーのメニュー（「肉」は含むが、他人データなのでヒットしない想定）
        $otherUser = User::factory()->create();
        $otherUserMenu = Menu::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '鶏の唐揚げ（肉料理）'
        ]);

        // 作成したユーザーとしてログインし、キーワード「肉」でAPIにリクエスト
        $response = $this->actingAs($user)
                         ->getJson('/api/menus/search-similar?keyword=肉');

        // 検証（アサーション）
        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonFragment(['id' => $myMenu1->id, 'name' => '肉じゃが'])
                 ->assertJsonFragment(['id' => $myMenu2->id, 'name' => '豚肉の生姜焼き'])
                 ->assertJsonMissing(['id' => $myMenuUnmatched->id])
                 ->assertJsonMissing(['id' => $otherUserMenu->id]);
    }

    /**
     * ソフトデリート（論理削除）されたメニューは検索結果に含まれない
     */
    public function test_soft_deleted_menus_are_not_included_in_search_results(): void
    {
        $user = User::factory()->create();

        // 削除済みのメニューを作成
        $deletedMenu = Menu::factory()->create([
            'user_id' => $user->id,
            'name' => '肉団子',
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($user)
                         ->getJson('/api/menus/search-similar?keyword=肉');

        $response->assertStatus(200)
                 ->assertJsonCount(0)
                 ->assertJsonMissing(['id' => $deletedMenu->id]);
    }
}

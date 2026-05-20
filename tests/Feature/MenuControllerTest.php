<?php

namespace Tests\Feature;

use App\Enums\MenuType;
use App\Models\Menu;
use App\Models\User;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MenuControllerTest extends TestCase
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
     * 【正常系】ユーザーは自分の新しいメニューを登録できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_register_new_menu(): void
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/menus', [
            'menu_name'          =>  'チキン南蛮',
            'type_id'       =>  MenuType::Main->value,
            'recipe_url'    =>  'https://recipe.example.com/chicken',
        ]);

        // 「どこかしらの画面に無事リダイレクトされたこと」を検証する
        $response->assertRedirect();

        $this->assertDatabaseHas('menus', [
            'user_id'   =>  $user->id,
            'name'      =>  'チキン南蛮',
        ]);
    }

    /**
     * 【正常系】ユーザーは自分の新しいメニュー（副菜A）を登録できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_register_new_side_dish_a(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/menus', [
            'menu_name' => 'ほうれん草のお浸し',
            'type_id' => MenuType::SideA->value, // 💡副菜Aを指定
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('menus', [
            'user_id' => $user->id,
            'name' => 'ほうれん草のお浸し',
            'type_id' => MenuType::SideA->value,
        ]);
    }

    /**
     * 【正常系】ユーザーは自分の新しいメニュー（副菜B）を登録できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_register_new_side_dish_b(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/menus', [
            'menu_name' => 'ポテトサラダ',
            'type_id' => MenuType::SideB->value, // 副菜Bを指定
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('menus', [
            'user_id' => $user->id,
            'name' => 'ポテトサラダ',
            'type_id' => MenuType::SideB->value,
        ]);
    }

    /**
     * 【異常系】料理名が空の場合はメニューを登録できない
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_menu_registration_fails_without_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/menus', [
            'nmenu_ame' => '', // わざと空にする
            'type_id' => MenuType::Main->value,
        ]);

        // バリデーションエラー（422）を期待
        $response->assertStatus(422);
    }

    /**
     * 【正常系】ユーザーは自分のメニューを削除（ソフトデリート）できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_soft_delete_own_menu(): void
    {
        $user = User::factory()->create();
        // あらかじめ削除対象のメニューを1つ作っておく
        $menu = Menu::factory()->create(['user_id' => $user->id]);

        // 削除API（DELETEリクエスト）を叩く
        $response = $this->actingAs($user)->deleteJson("/menus/{$menu->id}");

        $response->assertRedirect();

        // 論理削除（ソフトデリート）のチェック
        // assertSoftDeleted を使うことで、deleted_at に値が入った状態で残っているかを検証
        $this->assertSoftDeleted('menus', [
            'id' => $menu->id,
        ]);
    }

    /**
     * 【異常系】他人のメニューを削除することはできない
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_cannot_delete_others_menu(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        // 他人のメニューを作る
        $othersMenu = Menu::factory()->create(['user_id' => $other->id]);

        // 自分が他人のメニューを消そうとする
        $response = $this->actingAs($me)->deleteJson("/menus/{$othersMenu->id}");

        $response->assertRedirect();
    }

    /**
     * 【正常系】管理者（Admin）権限のユーザーは、他人のメニューでも削除できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_admin_user_can_delete_anyone_s_menu(): void
    {
        // 管理者ユーザーを作成する（role に admin を指定）
        $admin = User::factory()->create(['role' => 'admin']);

        // 一般ユーザー（メニューの持ち主）を作成する
        $menuOwner = User::factory()->create(['role' => 'general']);

        // 一般ユーザーのメニューを作成する
        $menu = Menu::factory()->create(['user_id' => $menuOwner->id]);

        // 管理者としてログインし、一般ユーザーのメニューに対して削除APIを叩く
        $response = $this->actingAs($admin)->deleteJson("/menus/{$menu->id}");

        // 管理者なので、一般画面へ無事リダイレクト（削除成功）することを期待
        $response->assertRedirect();

        // データベース上で、他人のメニューがしっかりソフトデリートされているか確認
        $this->assertSoftDeleted('menus', [
            'id' => $menu->id,
        ]);
    }
}

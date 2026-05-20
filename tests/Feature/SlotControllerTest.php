<?php

namespace Tests\Feature;

use App\Enums\MenuType;
use App\Models\Menu;
use App\Models\User;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlotControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // メニューのタイプ（主菜・副菜A・副菜B）のマスターデータを注入
        $this->seed(TypeSeeder::class);
    }

    /**
     * 【正常系】スロット画面に、自分が登録した主菜・副菜A・副菜Bのデータが正しく渡されている
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_slot_returns_one_main_and_two_side_dishes(): void
    {
        $user = User::factory()->create();

        // テスト用のメニューを自分のアカウントに登録（主菜1、副菜A1、副菜B1）
        $main = Menu::factory()->create(['user_id' => $user->id, 'name' => '唐揚げ', 'type_id' => MenuType::Main->value]);
        $sideA = Menu::factory()->create(['user_id' => $user->id, 'name' => 'きんぴら', 'type_id' => MenuType::SideA->value]);
        $sideB = Menu::factory()->create(['user_id' => $user->id, 'name' => '味噌汁', 'type_id' => MenuType::SideB->value]);

        // ルート「/」にアクセス
        $response = $this->actingAs($user)->get('/');

        $response->assertOk();


        // コントローラーからビューに渡された「menus」という変数（またはあなたが設定した変数名）の中に、
        // 登録した料理のIDが含まれているかを検証
        $response->assertViewHas('menus', function ($menus) use ($main, $sideA, $sideB) {
            return $menus->contains($main) && $menus->contains($sideA) && $menus->contains($sideB);
        });
    }

    /**
     * 【異常系】他人のメニューがスロット画面のデータに混ざることはない（鉄壁のセキュリティ）
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_slot_never_includes_others_menus(): void
    {
        // 「自分」と「他人」のユーザーをそれぞれ作成
        $me = User::factory()->create();
        $other = User::factory()->create();

        // 自分は「カレー」だけを登録
        $myMenu = Menu::factory()->create([
            'user_id' => $me->id,
            'name' => 'カレー',
            'type_id' => MenuType::Main->value
        ]);

        // 他人が「高級ステーキ」を登録（これが混ざったらバグ）
        $othersMenu = Menu::factory()->create([
            'user_id' => $other->id,
            'name' => '高級ステーキ',
            'type_id' => MenuType::Main->value
        ]);

        // 「自分」としてトップページ（/）にアクセス
        $response = $this->actingAs($me)->get('/');

        $response->assertOk();

        // ビューに渡されたデータ（menus）を厳密にチェック
        $response->assertViewHas('menus', function ($menus) use ($myMenu, $othersMenu) {
            // 自分の「カレー」は含まれている（true）
            // かつ、他人の「高級ステーキ」は【含まれていない】（! false）であることを検証
            return $menus->contains($myMenu) && !$menus->contains($othersMenu);
        });
    }

    /**
     * 【異常系】メニューが1件もない状態で画面を開いてもクラッシュしない（安全ガード）
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_slot_handles_empty_menu_safely(): void
    {
        // 新しいユーザーを作成（メニューは一切登録しない）
        $user = User::factory()->create();

        // メニューが空っぽの状態でトップページ（/）にアクセス
        $response = $this->actingAs($user)->get('/');

        // 500エラー（システムクラッシュ）にならず、無事に画面が開くこと（200 OK）
        $response->assertOk();

        // ビューに渡された「menus」という変数が、空っぽ（Empty）であることを検証
        $response->assertViewHas('menus', function ($menus) {
            return $menus->isEmpty();
        });
    }

    /**
     * 【異常系】メニューの登録件数が足りない場合、スロット画面に警告メッセージが表示される
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_slot_shows_warning_when_menu_count_is_insufficient(): void
    {
        // 新しいユーザーを作成（メニューは一切登録しない）
        $user = User::factory()->create();

        // メニューが足りない状態でトップページ（/）にアクセス
        $response = $this->actingAs($user)->get('/');

        // 画面自体はエラーにならず、無事に開くこと（200 OK）
        $response->assertOk();

        // 実際のBladeの記述と完全に一致する文字で検証
        $response->assertSee('スロットを回すための登録メニュー件数が足りません。');
        $response->assertSee('各料理タイプを3つ以上登録してください。');

        // 全角の「：」に合わせて厳密にチェック
        $response->assertSee('メイン：0/3');
        $response->assertSee('副菜A：0/3');
        $response->assertSee('副菜B：0/3');
    }
}

<?php

namespace Tests\Feature;

use App\Enums\MenuType;
use App\Models\Menu;
use App\Models\User;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MealRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストの事前準備（各テスト関数が実行される前に毎回自動で走るメソッド）
     */
    protected function setUp(): void
    {
        parent::setUp();

        // テストに必要な「TypeSeeder」だけをピンポイントで実行する
        $this->seed(TypeSeeder::class);
    }

    /**
     * 【正常系】ユーザーは自分のメニューで献立を保存できる
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_save_meal_record_with_own_menus(): void
    {
        $user = User::factory()->create();

        $main   = Menu::factory()->create(['user_id' =>  $user->id, 'type_id' => MenuType::Main->value, 'name' => '主菜'] );
        $sideA  = Menu::factory()->create(['user_id' =>  $user->id, 'type_id' => MenuType::SideA->value, 'name' => 'お浸し']);
        $sideB  = Menu::factory()->create(['user_id' =>  $user->id, 'type_id' => MenuType::SideB->value, 'name' => 'サラダ']);

        $response = $this->actingAs($user)->postJson('/meal-records', [
            'main_dish_id'  =>  $main->id,
            'sub_dish_a_id' =>  $sideA->id,
            'sub_dish_b_id' =>  $sideB->id,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('meal_records', [
            'user_id'   =>  $user->id,
        ]);
    }

    /**
     * 【異常系】他人のメニューIDは含まれている場合は献立を保存できない
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_cannot_save_meal_record_with_others_menus(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $otherMain = Menu::factory()->create(['user_id' => $other->id, 'type_id' => MenuType::Main->value, 'name' => '他人の主菜']);
        $mySideA   = Menu::factory()->create(['user_id' => $me->id, 'type_id' => MenuType::SideA->value, 'name' => '自分の副菜A']);
        $mySideB   = Menu::factory()->create(['user_id' => $me->id, 'type_id' => MenuType::SideB->value, 'name' => '自分の副菜B']);

        $response = $this->actingAs($me)->postJson('/meal-records', [
            'main_dish_id'  => $otherMain->id,
            'sub_dish_a_id' => $mySideA->id,
            'sub_dish_b_id' => $mySideB->id,
        ]);

        $response->assertStatus(422);
    }

    /**
     * 【異常系】同日に二重保存しようとすると409が返る
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_cannot_save_duplicate_meal_record_on_same_day(): void
    {
        $user = User::factory()->create();
        $main  = Menu::factory()->create(['user_id' => $user->id, 'type_id' => MenuType::Main->value, 'name' => '主菜']);
        $sideA = Menu::factory()->create(['user_id' => $user->id, 'type_id' => MenuType::SideA->value, 'name' => '副菜A']);
        $sideB = Menu::factory()->create(['user_id' => $user->id, 'type_id' => MenuType::SideB->value, 'name' => '副菜B']);

        $params = [
            'main_dish_id'  => $main->id,
            'sub_dish_a_id' => $sideA->id,
            'sub_dish_b_id' => $sideB->id,
        ];

        $this->actingAs($user)->postJson('/meal-records', $params)->assertOk();

        $response = $this->actingAs($user)->postJson('/meal-records', $params);

        $response->assertStatus(409);
    }

}

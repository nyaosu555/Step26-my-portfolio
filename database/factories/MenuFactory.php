<?php

namespace Database\Factories;

use App\Enums\MenuType;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Enumのケースからランダムに1つ選ぶ
        $randomType = fake()->randomElement(MenuType::cases());
        return [
            // ユーザーIDを未指定で Menu::factory()->create()した場合
            // 自動的に新しい User も裏で作って紐付けしてくれる用の設定
            'user_id'       =>  User::factory(),

            // 選ばれたEnumの「生の値（1, 2, 3）」を代入
            'type_id'       =>  $randomType->value,

            'name'          =>  fake()->word(),
            'image_path'    =>  null,
            'recipe_url'    =>  null,
            'memo'          =>  null,
        ];
    }
}

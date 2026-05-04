<?php

namespace App\Http\Requests;

use App\Enums\MenuType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreMealRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            //
            $userId = Auth::id();    //今ログインしている人のIDを取得

            return [
                // 主菜: 自分のメニュー かつ typeが主菜
                'main_dish_id' => [
                    'required', Rule::exists('menus', 'id')
                    ->where('user_id', $userId)->where('type_id', MenuType::Main->value)
                ],
                // 副菜A: 自分のメニュー かつ typeが副菜A
                'sub_dish_a_id' => [
                    'required', Rule::exists('menus', 'id')
                    ->where('user_id', $userId)->where('type_id', MenuType::SideA->value)
                ],
                // 副菜B: 自分のメニュー かつ typeが副菜B
                'sub_dish_b_id' => [
                    'required', Rule::exists('menus', 'id')
                    ->where('user_id', $userId)->where('type_id', MenuType::SideB->value)
                ],
            ];
    }

    public function message(): array
    {
        return [
            'main_dish_id.exists' => '選択された主菜は無効です。',
            'main_dish_a_id.exists' => '選択された副菜Aは無効です。',
            'main_dish_b_id.exists' => '選択された副菜Bは無効です。',
        ];
    }
}

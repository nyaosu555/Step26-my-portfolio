<?php

namespace App\Http\Requests;

use App\Enums\MenuType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 1. このリクエストを許可するかどうか
     */
    public function authorize(): bool
    {
        // 誰でも（ログインしていれば）OKにするため true に変更
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 2. バリデーションルール本体
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu_name' => 'required|string|max:255',
            'type_id' => [
                'required',
                'integer',
                'exists:types,id',
                new Enum(MenuType::class) // Enumで定義した値(1,2,3)以外は弾くことで厳しくチェック
            ],
            'image_path' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'recipe_url' => 'nullable|url|max:255',
            'memo' => 'nullable|string|max:1000',
        ];
    }

    /**
     * 3. エラーメッセージのカスタマイズ（任意）
     */
    public function messages(): array
    {
        return [
            'image_path.max' => "画像サイズは2M以下にしてください。\n別の画像を選ぶか、画像なしで登録する場合は、このまま「登録」を押してください。",
            'image_path.image' => "選択されたファイルは画像ではありません。\n別の画像を選ぶか、画像なしで登録する場合は、このまま「登録」を押してください。",
            'image_path.mimes' => "png, jpg, jpeg, gif形式の画像を選択してください。\n別の画像を選ぶか、画像なしで登録する場合は、このまま「登録」を押してください。",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // // 成功時と同じ形式で「失敗メッセージ」をセッションに仕込む
        // session()->flash('message', 'メニューの登録に失敗しました。各項目のエラーを解消してください。');
        // session()->flash('type', 'danger');

        // // 本来のバリデーション失敗時の動き（エラーを持って前の画面に戻る）を実行
        // parent::failedValidation($validator);

        // リクエストが PATCH または PUT（＝更新処理）の場合
        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            session()->flash('message', 'メニューの更新に失敗しました。各項目のエラーを解消してください。');
        } else {
            // 💡 それ以外（＝POST、新規登録処理）の場合
            session()->flash('message', 'メニューの登録に失敗しました。各項目のエラーを解消してください。');
        }

        // 共通のタイプ（赤色）をセット
        session()->flash('type', 'danger');

        // 本来のバリデーション失敗時の動きを実行
        parent::failedValidation($validator);

    }

}

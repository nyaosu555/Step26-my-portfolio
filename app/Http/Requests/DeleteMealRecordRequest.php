<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMealRecordRequest extends FormRequest
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
        return [
            // ids自体が配列で、少なくとも1つ選ばれていること
            'ids'   =>  ['required', 'array', 'min:1'],
            // 配列の各要素（ids.*）が数値であり、かつ、meal_recordsテーブルに存在すること
            'ids.*' =>  ['integer', 'exists:meal_records,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required'  =>  '削除する献立が選択されていません。',
            'ids.*exists'   =>  '指定された献立の一部が見つかりませんでした。画面を更新してください。',
        ];
    }
}

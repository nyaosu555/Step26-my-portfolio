<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    //リレーション：一つのタイプ(メイン/副菜など)は、複数のメニューを持つ
    // メイン-ハンバーグ、唐揚げ、肉じゃが・・・
    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    // リレーション：一つのタイプは、複数の献立記録明細をもつ
    public function mealRecordItems(): HasMany
    {
        return $this->hasMany(MealRecordItem::class);
    }
}

<?php

namespace App\Models;

use App\Enums\MenuType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MealRecord extends Model
{
    //
    protected $fillable = [
        'user_id',
        'date',
    ];

    // リレーション：この記録には、複数の料理（アイテム）が含まれる
    public function mealRecordItems(): HasMany
    {
        return $this->hasMany(MealRecordItem::class);
    }

    // リレーション：この記録は1人のユーザーに属する
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 主菜の明細を取得
    public function mainDish() : HasOne
    {
        return $this->hasOne(MealRecordItem::class)->where('type_id', MenuType::Main->value);
    }
    // 副菜Aの明細を取得
    public function sideDishA() : HasOne
    {
        return $this->hasOne(MealRecordItem::class)->where('type_id', MenuType::SideA->value);
    }
    // 副菜Bの明細を取得
    public function sideDishB() : HasOne
    {
        return $this->hasOne(MealRecordItem::class)->where('type_id', MenuType::SideB->value);
    }

}

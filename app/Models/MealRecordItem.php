<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealRecordItem extends Model
{
    protected $fillable = [
        'meal_record_id', 'menu_id', 'type_id',
    ];

    // リレーション：この明細は、どの献立記録に属しているか
    public function mealRecord(): BelongsTo
    {
        return $this->belongsTo(MealRecord::class);
    }

    // リレーション：この明細は、ひとつのメニューを持つ
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class)->withTrashed();
    }

    // リレーション：この明細は、ひとつの料理タイプ（メイン/副菜など）を持つ
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}

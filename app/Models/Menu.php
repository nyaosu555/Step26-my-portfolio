<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type_id',
        'image_path',
        'recipe_url',
        'memo',
    ];

    // リレーション：1行のメニューは、1つのタイプを持つ
    public function type(): BelongsTo {
        return $this->belongsTo(Type::class);
    }

    // リレーション：1行のメニューは1人のユーザーを持つ
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // リレーション：1行のメニューはたくさんの献立記録明細をもつ
    public function mealRecordItems(): HasMany
    {
        return $this->hasMany(MealRecordItem::class);
    }
}

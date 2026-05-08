<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meal_record_items', function (Blueprint $table) {
            // 1. 今ある外部キー制約を削除
            // 制約めいは「テーブル名_カラム名_foreign」
            $table->dropForeign(['menu_id']);

            // 2. 新しい制約を作る
            // cascadeOnDeleteを外すと、親が消えても子が消えなくなる(restrict)
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_record_items', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);

            // ロールバック時は元の cascadeOnDelete に戻す
            $table->foreign('menu_id')->references('id')->on('menus')->cascadeOnDelete();

        });
    }
};

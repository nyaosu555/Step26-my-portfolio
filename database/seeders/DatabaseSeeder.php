<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // ここに実行したいシーダーを順番に並べます
        $this->call([
            TypeSeeder::class,
            // もしMenuSeederも完成しているならここに追加できます
            MenuSeeder::class,
            MealRecordSeeder::class,
        ]);
    }
}

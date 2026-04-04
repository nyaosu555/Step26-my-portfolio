<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1. 管理者を2人作成
        $admins = [
            ['name' => '管理者A', 'email' => 'admin1@example.com'],
            ['name' => '管理者B', 'email' => 'admin2@example.com'],
        ];

        foreach($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ],
            );
        }

        // 2. 一般ユーザー3人作成
        $generals = [
            ['name' => 'ユーザー1', 'email' => 'user1@example.com'],
            ['name' => 'ユーザー2', 'email' => 'user2@example.com'],
            ['name' => 'ユーザー3', 'email' => 'user3@example.com'],
        ];

        foreach($generals as $general) {
            User::updateOrCreate(
                ['email' => $general['email']],
                [
                    'name' => $general['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'general',
                ]
            );
        }
    }
}

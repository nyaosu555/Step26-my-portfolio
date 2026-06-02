<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 'admin'という名前のゲート（門番）を定義
        Gate::define('admin', function(User $user) {
            // ログイン中のユーザーのroleが「admin」なら通す
            return $user->role === 'admin';
        });

        // 本番環境（Fly.io）の場合だけ、強制的にHTTPSにする設定
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}

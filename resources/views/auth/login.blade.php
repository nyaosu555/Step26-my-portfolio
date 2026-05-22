<x-guest-layout>
    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}
    <h2 class="text-[#DA5019] font-bold text-2xl mb-6">ログイン</h2>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email Address -->
        {{-- メールアドレス --}}
        <div class="text-left">
            {{-- ラベル：色を#DA5019にする --}}
            {{-- <x-input-label for="email" :value="__('Email')" /> --}}
            <x-input-label for="email" :value="__('Email')" class="text-[#DA5019] font-bold text-lg mb-1" />
            {{-- メアド入力欄：枠線を#DA5019, フォーカス時のデフォルトの枠線を削除しカスタム --}}
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" /> --}}
            <x-text-input id="email" class="block mt-1 w-full !border-[#DA5019] focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:border-none" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="example.email.com" />

            {{-- 未入力時のエラー --}}
            @error('email')
                @if($message !== __('auth.failed'))
                    <p class="mt-2 text-red-500 font-bold text-xs text-left">{{ $message}}</p>
                @endif
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4 text-left">
            <x-input-label for="password" :value="__('Password')"  class="text-[#DA5019] font-bold text-lg mb-1" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            {{-- <x-input-error :messages="$errors->get('password')" class="mt-2" /> --}}
            {{-- 未入力時のエラー --}}
            @error('password')
                @if($message !== __('auth.failed'))
                    <p class="mt-2 text-red-500 font-bold text-xs text-left">{{ $message}}</p>
                @endif
            @enderror
        </div>

       {{-- <div>
            <x-input-error :messages="$errors->get('email')" class="mt-4" />
        </div> --}}
        {{-- 認証失敗時のみ表示 --}}
        @if ($errors->has('email') && $errors->first('email') === __('auth.failed'))
            <div class="mt-8 mb-4">
                <p class="text-red-500 font-bold text-sm ml-1 text-center">{{ __('auth.failed')}}</p>
            </div>
        @endif

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> --}}

        <div class="flex items-center justify-center mt-8">
            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}

            <x-primary-button class="px-14 py-2 md:py-4 md:px-20 bg-[#FFB908] text-base md:text-[20px] ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        <div class="mt-4">
            <a href="{{ route('register')}}" class="text-sm underline hover:opacity-[0.7]">アカウントを作成されていない方はこちらからどうぞ。<br><b>サインアップ</b></a>
        </div>
    </form>
</x-guest-layout>

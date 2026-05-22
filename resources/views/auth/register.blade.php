<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <!-- Name -->
        <div class="text-left">
            <x-input-label for="name" :value="__('ユーザー名')" class="text-[#DA5019] font-bold text-lg mb-1" />
            <x-text-input id="name" class="block mt-1 w-full placeholder-gray-300" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="山田  太郎" />
            {{-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
            @error('name')
                    <p class="mt-2 text-red-500 font-bold text-xs text-left">{{ $message}}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="text-left mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-[#DA5019] font-bold text-lg mb-1" />
            <x-text-input id="email" class="block mt-1 w-full placeholder-gray-300" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="test" />
            {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
            @error('email')
                    <p class="mt-2 text-red-500 font-bold text-xs text-left">{{ $message}}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="text-left mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-[#DA5019] font-bold text-lg mb-1" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            {{-- <x-input-error :messages="$errors->get('password')" class="text-[#DA5019] font-bold text-lg mb-1 mt-2" /> --}}
            @error('password')
                    <p class="mt-2 text-red-500 font-bold text-xs text-left">{{ $message}}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="text-left mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-[#DA5019] font-bold text-lg mb-1 mt-2" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            {{-- <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /> --}}
        </div>

        <div class="flex flex-col items-center justify-end mt-4">
            <x-primary-button  class="px-14 py-2 md:py-4 md:px-20 bg-[#FFB908] text-base md:text-[20px] ms-3">
                {{ __('新規登録') }}
            </x-primary-button>

            {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a> --}}
            <div class="mt-4">
                <a href="{{ route('login')}}" class="text-sm underline hover:opacity-[0.7]">すでにアカウントをお持ちの方はこちら<br><b>ログイン</b></a>
            </div>
        </div>
    </form>
</x-guest-layout>

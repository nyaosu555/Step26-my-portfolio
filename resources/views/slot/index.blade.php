<x-app-layout>
    {{-- 全体の背景（料理の写真など） --}}
    <div class="relative min-h-screen bg-cover bg-center flex items-center justify-center flex-col p-4"
        style="background-image: url('{{ asset('images/background-image.png') }}');"
    >
        {{-- 背景を暗くして文字を見やすくするオーバーレイ --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        {{-- ページタイトル --}}
        <h1 class="text-[#ffffff] text-3xl md:text-5xl font-bold mb-8 drop-shadow-sm">
            今日のおかずなんにしよ
            <div class="h-1.5 w-[245px] bg-[#D97706] mx-auto mt-2 rounded-full"></div>
        </h1>
        {{-- メニュー登録件数が足りない時のメッセージ --}}
        @if (!$isReady)
            <div class="absolute top-[40%] z-20 w-full max-w-4xl mb-6 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded shadow-md" role="alert">
                <p class="font-bold text-lg">⚠️スロットを回すための登録メニュー件数が足りません。</p>
                <p>各料理タイプを3つ以上登録してください。</p>
                <div class="flex gap-4 mt-2 text-sm font-semibold">
                    <span>メイン：{{ $counts['main'] }}/3</span>
                    <span>副菜A：{{ $counts['side_a'] }}/3</span>
                    <span>副菜B：{{ $counts['side_b'] }}/3</span>
                </div>
                <a href="{{ route('menus.index') }}">👉メニュー管理画面で登録する</a>
            </div>
        @endif
        {{-- メインの黄色いカード --}}
        <div class="relative z-10 w-full max-w-4xl bg-[#FEE5A5] rounded-[2rem] shadow-2xl p-8 md:p-12 text-center">
            {{-- スロットコンテナ --}}
            <div class="flex justify-around items-center gap-[24px]">
                {{-- スロット1:メイン --}}
                <div class="space-y-4 w-[calc((100%-48px)/2)]">
                    <h2 class="text-[#d97706] font-bold text-xl">メイン</h2>
                    <div x-data="{ rolling: false }" class="space-y-4">
                        <div class="slot-container aspect-square bg-white rounded-2xl border-4 border-[#d97706] overflow-hidden shadow-inner">
                            {{-- ここにJSで生成した<ul><li>が入る --}}
                            <ul id="slot-main" class="absolute inset-0 m-0 p-0 list-none"></ul>
                            <div class="absolute inset-0 pointer-events-none z-10"
                                style="background: linear-gradient(to bottom,
                                    rgba(0,0,0,0.8) 0%,
                                    rgba(0,0,0,0) 17%,
                                    rgba(0,0,0,0) 83%,
                                    rgba(0,0,0,0.8) 100%
                                );"
                            >
                            </div>
                        </div>
                        {{-- $isReadyがfalseならスタートボタン無効化 --}}
                        <button
                        @if ($isReady)
                            @click="rolling = !rolling; toggleSlot(1, rolling)"
                        @endif
                            {{-- :class="rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]'" --}}
                            :class="!{{ $isReady ? 'true' : 'false' }} ? 'bg-red-400 cursor-not-allowed opacity-50' : (rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]')"
                            class="w-full py-3 bg-[#fbbf24] hover:bg-[#f59e0b] text-white font-bold rounded-xl shadow-md transition-all active:scale-95"
                        >
                            {{-- <span x-text="rolling ? 'ストップ' : 'スタート'"></span> --}}
                            <span x-text="!{{ $isReady ? 'true' : 'false' }} ? '準備中' : (rolling ? 'ストップ' : 'スタート')"></span>
                        </button>
                    </div>
                </div>
                {{-- スロット2:副菜A --}}
                <div class="space-y-4 w-[calc((100%-48px)/2)]">
                    <h2 class="text-[#d97706] font-bold text-xl">副菜A</h2>
                    <div x-data="{ rolling: false }" class="space-y-4">
                        <div class="slot-container aspect-square bg-white rounded-2xl border-4 border-[#d97706] overflow-hidden shadow-inner">
                            {{-- ここにJSで生成した<ul><li>が入る --}}
                            <ul id="slot-sub-a" class="absolute inset-0 m-0 p-0 list-none"></ul>
                            <div class="absolute inset-0 pointer-events-none z-10"
                                style="background: linear-gradient(to bottom,
                                    rgba(0,0,0,0.8) 0%,
                                    rgba(0,0,0,0) 17%,
                                    rgba(0,0,0,0) 83%,
                                    rgba(0,0,0,0.8) 100%
                                );"
                            >
                            </div>
                        </div>
                        {{-- <button
                            @click="rolling = !rolling; toggleSlot(2, rolling)"
                            :class="rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]'"
                            class="w-full py-3 bg-[#fbbf24] hover:bg-[#f59e0b] text-white font-bold rounded-xl shadow-md transition-all active:scale-95"
                        >
                            <span x-text="rolling ? 'ストップ' : 'スタート'"></span>
                        </button> --}}
                        {{-- $isReadyがfalseならスタートボタン無効化 --}}
                        <button
                        @if ($isReady)
                            @click="rolling = !rolling; toggleSlot(2, rolling)"
                        @endif
                            {{-- :class="rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]'" --}}
                            :class="!{{ $isReady ? 'true' : 'false' }} ? 'bg-red-400 cursor-not-allowed opacity-50' : (rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]')"
                            class="w-full py-3 bg-[#fbbf24] hover:bg-[#f59e0b] text-white font-bold rounded-xl shadow-md transition-all active:scale-95"
                        >
                            {{-- <span x-text="rolling ? 'ストップ' : 'スタート'"></span> --}}
                            <span x-text="!{{ $isReady ? 'true' : 'false' }} ? '準備中' : (rolling ? 'ストップ' : 'スタート')"></span>
                        </button>
                    </div>
                </div>
                {{-- スロット3:副菜B --}}
                <div class="space-y-4 w-[calc((100%-48px)/2)]">
                    <h2 class="text-[#d97706] font-bold text-xl">副菜B</h2>
                    <div x-data="{ rolling: false }" class="space-y-4">
                        <div class="slot-container aspect-square bg-white rounded-2xl border-4 border-[#d97706] overflow-hidden shadow-inner">
                        {{-- ここにJSで生成した<ul><li>が入る --}}
                            <ul id="slot-sub-b" class="absolute inset-0 m-0 p-0 list-none"></ul>
                            <div class="absolute inset-0 pointer-events-none z-10"
                                style="background: linear-gradient(to bottom,
                                    rgba(0,0,0,0.8) 0%,
                                    rgba(0,0,0,0) 17%,
                                    rgba(0,0,0,0) 83%,
                                    rgba(0,0,0,0.8) 100%
                                );"
                            >
                            </div>
                        </div>
                        {{-- <button
                            @click="rolling = !rolling; toggleSlot(3, rolling)"
                            :class="rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]'"
                            class="w-full py-3 bg-[#fbbf24] hover:bg-[#f59e0b] text-white font-bold rounded-xl shadow-md transition-all active:scale-95"
                        >
                            <span x-text="rolling ? 'ストップ' : 'スタート'"></span>
                        </button> --}}
                        {{-- $isReadyがfalseならスタートボタン無効化 --}}
                        <button
                        @if ($isReady)
                            @click="rolling = !rolling; toggleSlot(3, rolling)"
                        @endif
                            {{-- :class="rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]'" --}}
                            :class="!{{ $isReady ? 'true' : 'false' }} ? 'bg-red-400 cursor-not-allowed opacity-50' : (rolling ? 'bg-red-500 hover:bg-red-600' : 'bg-[#FBBF24] hover:bg-[#F59E0B]')"
                            class="w-full py-3 bg-[#fbbf24] hover:bg-[#f59e0b] text-white font-bold rounded-xl shadow-md transition-all active:scale-95"
                        >
                            {{-- <span x-text="rolling ? 'ストップ' : 'スタート'"></span> --}}
                            <span x-text="!{{ $isReady ? 'true' : 'false' }} ? '準備中' : (rolling ? 'ストップ' : 'スタート')"></span>
                        </button>
                    </div>
                </div>
            </div>
            {{-- ナビゲーション --}}
            <div class="mt-10 flex justify-end space-x-6 text-[#d97706] font-bold text-sm md:text-base">
                <a href="#" id="save-button" class="flex items-center hover:opacity-70"><span class="mr-1">⬇️</span>献立を保存する</a>
                <a href="/meal-records" class="flex items-center hover:opacity-70"><span class="mr-1">🕐</span>保存した献立一覧</a>
                @can('admin')
                    <a href="{{ route('menus.index') }}" class="flex items-center hover:opacity-70"><span class="mr-1">⬆️</span>メニュー管理</a>
                @else
                    <button disabled class="flex items-center opacity-30 cursor-not-allowed" title="管理者権限が必要です"><span class="mr-1">⬆️</span>メニュー管理（制限中）</a>
                @endcan
            </div>
        </div>
    </div>
    @push('scripts')
        @vite('resources/js/slot.js')
    @endpush
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if(window.initSlot) {
            window.initSlot(@json($menus));
        }
    });
</script>

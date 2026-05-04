<x-app-layout>
        {{-- メニュー登録件数が足りない時のメッセージ --}}
        @if (!$isReady)
            <div class="absolute top-[40%] left-[50%] translate-x-[-50%] z-20 w-full max-w-4xl mb-6 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded shadow-md" role="alert">
                <p class="mb-4 font-bold text-lg flex gap-2 items-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-orange-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>スロットを回すための登録メニュー件数が足りません。</p>
                <p>各料理タイプを3つ以上登録してください。</p>
                <div class="flex gap-4 mt-2 text-sm font-semibold">
                    <span>メイン：{{ $counts['main'] }}/3</span>
                    <span>副菜A：{{ $counts['side_a'] }}/3</span>
                    <span>副菜B：{{ $counts['side_b'] }}/3</span>
                </div>
                <a href="{{ route('menus.index') }}" class="mt-4 inline-flex items-center gap-2 hover:underline"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
</svg>
メニュー管理画面で登録する</a>
            </div>
        @endif

        <x-flash-message />

        <div class="relative z-10 w-[80%] mx-auto bg-[#fee5a5] rounded-[2em] shadow-2xl p-8 md:p-10 text-center flex flex-col gap-10 justify-end">
            {{-- メインの黄色いカード --}}
                {{-- スロットコンテナ --}}
                <div class="flex justify-around items-center gap-[24px]">
                    {{-- スロット1:メイン --}}
                    <div class="space-y-4 w-[calc((100%-48px)/3)]">
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
                                {{-- @click="rolling = !rolling; toggleSlot(1, rolling)" --}}
                                @click="rolling = !rolling; toggleSlot(window.MENU_TYPES.MAIN, rolling)"
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
                    <div class="space-y-4 w-[calc((100%-48px)/3)]">
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
                                @click="rolling = !rolling; toggleSlot(window.MENU_TYPES.SIDE_A, rolling)"
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
                    <div class="space-y-4 w-[calc((100%-48px)/3)]">
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
                                {{-- @click="rolling = !rolling; toggleSlot(3, rolling)" --}}
                                @click="rolling = !rolling; toggleSlot(window.MENU_TYPES.SIDE_B, rolling)"
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
                <div id="slot-result-display"
     class="hidden mt-6 p-4 bg-white/50 backdrop-blur-sm rounded-2xl border-2 border-dashed border-orange-300 flex items-center justify-center">
    <div class="text-orange-900 font-bold text-lg md:text-xl flex items-center gap-3">
        <span class="text-sm text-orange-600 font-normal">本日の献立：</span>
        <span id="display-main"></span>
        <span class="text-orange-300">/</span>
        <span id="display-sub-a"></span>
        <span class="text-orange-300">/</span>
        <span id="display-sub-b"></span>
    </div>
</div>
                {{-- ナビゲーション --}}
                <x-sub-navigation current="slot" />
                {{-- <div class="mt-10 flex justify-end space-x-6 text-[#d97706] font-bold text-sm md:text-base">
                    <a href="#" id="save-button" class="flex items-center hover:opacity-70"><span class="mr-1">⬇️</span>献立を保存する</a>
                    <a href="/meal-records" class="flex items-center hover:opacity-70"><span class="mr-1">🕐</span>保存した献立一覧</a>
                    @can('admin')
                        <a href="{{ route('menus.index') }}" class="flex items-center hover:opacity-70"><span class="mr-1">⬆️</span>メニュー管理</a>
                    @else
                        <button disabled class="flex items-center opacity-30 cursor-not-allowed" title="管理者権限が必要です"><span class="mr-1">⬆️</span>メニュー管理（制限中）</a>
                    @endcan
                </div> --}}
        </div>
    @push('scripts')
        {{-- JavaScript側にPHPのEnum値を定数として渡す --}}
        <script>
            window.MENU_TYPES = {
                MAIN: {{ \App\Enums\MenuType::Main->value }},
                SIDE_A: {{ \App\Enums\MenuType::SideA->value }},
                SIDE_B: {{ \App\Enums\MenuType::SideB->value }}
            };
        </script>
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

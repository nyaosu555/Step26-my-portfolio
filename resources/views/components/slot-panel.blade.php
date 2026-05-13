@props(['id', 'label', 'type', 'isReady'])

<div class="space-y-4 w-[calc((100%-48px)/3)]">
    {{-- <h2 class="text-[#d97706] font-bold text-xl">メイン</h2> --}}
    <h2 class="text-[#d97706] font-bold text-xl">{{ $label }}</h2>
    <div x-data="{ rolling: false }" class="space-y-4">
        <div class="slot-container aspect-square bg-white rounded-2xl border-4 border-[#d97706] overflow-hidden shadow-inner">
            {{-- ここにJSで生成した<ul><li>が入る --}}
            {{-- <ul id="slot-main" class="absolute inset-0 m-0 p-0 list-none"></ul> --}}
            <ul id="{{ $id }}" class="absolute inset-0 m-0 p-0 list-none"></ul>
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
            {{-- @click="rolling = !rolling; toggleSlot(window.MENU_TYPES.MAIN, rolling)" --}}
            @click="rolling = !rolling; toggleSlot({{ $type }}, rolling)"
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

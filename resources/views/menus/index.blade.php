<x-app-layout>
    {{-- 1. x-dataで「今どのタブを開いているか」という状態を定義 --}}
    {{-- session('show_create')があれば登録画面、なければ一覧を初期表示にする --}}
    <div
        x-data="{ tab: '{{ session('type') === 'success' ? 'list' : 'create' }}' }"
        class="max-w-4xl mx-auto p-6"
    >
        <x-flash-message
            :message="session('message')"
            :type="session('type')" />
        <h1 class="text-center text-2xl font-bold mb-8">メニュー管理</h1>

        {{-- 2. タブのスイッチ --}}
        <div class="flex justify-center space-x-8 border-b border-gray-200 mb-8">
            <button
                @click="tab = 'create'"
                :class="tab === 'create' ? 'border-b-2 border-orange-500 text-orange-600 font-bold' : 'text-gray-400' "
                class="pb-2 px-4 transition-all"
            >
                メニュー登録
            </button>
            <button
                @click="tab = 'list'"
                :class="tab === 'list' ? 'border-b-2 border-orange-500 text-orange-600 font-bold' : 'text-gray-400' "
                class="pb-2 px-4 transition-all"
            >
                登録メニュー一覧
            </button>
        </div>

        {{-- 3. コンテンツの切り替え --}}

        {{-- メニュー登録フォーム --}}
        <div x-show="tab === 'create'" x-cloak>
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                @include('menus.partials.create-form')   {{--別ファイルで管理--}}
            </div>
        </div>

        {{-- 登録メニュー一覧 --}}
        <div x-show="tab === 'list'" x-cloak>
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <p class="text-orange-700 font-bold mb-6 text-[18px]">
                    登録件数: {{ $menus->count() }}件
                </p>
                @include('menus.partials.list-items')   {{--別ファイルで管理--}}
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/menu-create.js')
    @endpush
</x-app-layout>

<style>
    /* 読み込み時に一瞬チラつくのを防ぐ魔法の呪文 */
    [x-cloak] { display: none !important; }
</style>

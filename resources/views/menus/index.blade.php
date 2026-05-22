<x-app-layout>
    {{-- x-dataで「今どのタブを開いているか」という状態を定義 --}}
    {{-- session('show_create')があれば登録画面、なければ一覧を初期表示にする --}}
    <div
        x-data="{
            tab: '{{ (request()->has('page') || session('type') === 'success') ? 'list' : 'create' }}' }"
        class="relative z-10 w-[98%] md:w-[80%] mx-auto bg-[#fee5a5] rounded-[2em] shadow-2xl p-4 md:p-10 text-center"
    >
        <x-flash-message
            :message="session('message')"
            :type="session('type')" />
        <div class="mx-auto py-6 sm:py-12 flex flex-col gap-6 sm:gap-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                メニュー管理
            </h2>

            {{-- タブのスイッチ --}}
            <div class="flex justify-start space-x-8 border-b border-gray-200 mb-8">
                <button
                    @click="tab = 'create';
                        window.history.replaceState(null, '', window.location.pathname);
                    "
                    :class="tab === 'create' ? 'border-b-2 border-orange-500 text-orange-600 font-bold' : 'text-gray-400' "
                    class="pb-2 px-4 transition-all text-sm md:text-base"
                >
                    メニュー登録
                </button>
                <button
                    @click="window.location.href = '{{ route('menus.index') }}?page=1'"
                    :class="tab === 'list' ? 'border-b-2 border-orange-500 text-orange-600 font-bold' : 'text-gray-400' "
                    class="pb-2 px-4 transition-all text-sm md:text-base"
                >
                    登録メニュー一覧
                </button>
            </div>

            {{-- コンテンツの切り替え --}}

            {{-- メニュー登録フォーム --}}
            <div x-show="tab === 'create'" x-cloak>
                <div class="bg-white p-4 md:p-8 rounded-2xl shadow-sm">
                    @include('menus.partials.create-form')   {{--別ファイルで管理--}}
                </div>
            </div>

            {{-- 登録メニュー一覧 --}}
            <div x-show="tab === 'list'" x-cloak>
                <div class="bg-white p-4 md:p-8 rounded-2xl shadow-sm">
                    <p class="text-orange-700 font-bold mb-6 text-[18px]">
                        登録件数: {{ $menus->total() }}件
                    </p>
                    @include('menus.partials.list-items')   {{--別ファイルで管理--}}
                </div>
                {{-- ページネーション --}}
                <div class="mt-6 px-2">
                    {{ $menus->links() }}
                </div>
            </div>
        {{-- ナビゲーション --}}
        <x-sub-navigation current="menus" />
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

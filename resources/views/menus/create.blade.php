{{-- 親レイアウトを継承する --}}
<x-app-layout>

{{-- メインコンテンツ（HTML）部分 --}}
    <x-slot name="header">
        <h1>メニュー登録</h1>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" id="menu-form">
                    @csrf
                    {{-- メニュー名 --}}
                    <div>
                        <label for="menu_name">メニュー名※</label>
                        <input type="text" name="menu_name" value="{{ old('menu_name') }}">
                        @error('menu_name')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- ジャンル --}}
                    <div>
                        <label for="">ジャンル※</label>
                        <input type="radio" name="type_id" value="{{ \App\Enums\MenuType::Main->value }}" {{old('type_id') == \App\Enums\MenuType::Main->value ? 'checked' : ''}}>メイン
                        <input type="radio" name="type_id" value="{{ \App\Enums\MenuType::SideA->value }}" {{old('type_id') == \App\Enums\MenuType::SideA->value ? 'checked' : ''}}>副菜A
                        <input type="radio" name="type_id" value="{{ \App\Enums\MenuType::SideB->value }}" {{old('type_id') == \App\Enums\MenuType::SideB->value ? 'checked' : ''}}>副菜B
                        @error('type_id')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 写真（ファイル選択） --}}
                    <div>
                        <label for="">写真</label>
                        <input type="file" name="image" id="imageInput" onchange="previewImage(this)">
                    </div>
                    {{-- 画像のプレビュー表示エリア --}}
                    <div class="mt-2 w-[400px] h-[250px] relative">
                        <img src="" alt="" id="imagePreview" alt="メニュー画像プレビュー" class="w-full h-full border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 object-contain">
                        <span id="placeholder" class="text-gray-400 text-xs text-center p-2">画像が選択されていません。</span>
                        {{-- プレビュー画像削除ボタン --}}
                        <button type="button" id="deleteImageBtn" onclick="clearImage()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 hidden">✕</button>
                    </div>

                    <button type="submit" id="save-menu-btn" class="mt-10">メニューを追加</button>
                </form>
            </div>
        </div>
    </div>

    {{-- JSを親の@stack('script')に送り込む --}}
    @push('scripts')
        @vite('resources/js/menu-create.js')
    @endpush
</x-app-layout>

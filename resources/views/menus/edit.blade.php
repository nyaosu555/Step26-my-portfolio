<x-flash-message
    :message="session('message')"
    :type="session('type')" />
<x-app-layout>
    {{-- 💡 新規登録画面の親ファイルと100%同じデザインのコンテナ（背景色、角丸、影、サイズ感） --}}
    <div class="relative z-10 w-[98%] md:w-[80%] mx-auto bg-[#fee5a5] rounded-[2em] shadow-2xl p-4 md:p-10 text-center">

        <div class="mx-auto py-6 sm:py-12 flex flex-col gap-6 sm:gap-10">

            {{-- 💡 タイトルを「メニュー編集」に変更 --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                メニュー編集
            </h2>

            {{-- 💡 編集専用のフォームコンテナ（白背景の丸角カード） --}}
            <div class="bg-white p-4 md:p-8 rounded-2xl shadow-sm">

                <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data" id="menu-form" class="space-y-6 text-left">
                    @csrf
                    @method('PATCH') {{-- 更新のためのPATCHメソッド --}}

                    {{-- メニュー名 --}}
                    <div class="flex flex-col items-start gap-2 mt-4">
                        <label for="menu_name" class="text-[#DA5019] font-bold">メニュー名※</label>
                        {{-- 既存のデータを初期値にセット --}}
                        <input type="text" name="menu_name" value="{{ old('menu_name', $menu->name) }}" class="w-full md:w-[70%] border border-[#DA5019] focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:border-none rounded-md shadow-sm p-2">
                        @error('menu_name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ジャンル --}}
                    <div class="flex flex-col items-start gap-2 pt-2">
                        <label class="block text-[#DA5019] font-bold">ジャンル※</label>
                        <div class="flex flex-wrap gap-4 sm:gap-6">
                            @foreach ($types as $type)
                                <label for="type_{{ $type->id }}" class="inline-flex items-center cursor-pointer bg-orange-50/50 hover:bg-orange-50 px-3 py-1.5 rounded-lg border border-transparent transition select-none">
                                    {{-- 既存のジャンルと一致したら最初からチェック --}}
                                    <input type="radio" name="type_id" value="{{ $type->id }}"
                                        {{ old('type_id', $menu->type_id) == $type->id ? 'checked' : '' }}
                                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500"
                                        id="type_{{ $type->id }}"
                                    >
                                    <span class="ml-2 text-[#DA5019] font-medium text-sm sm:text-base">{{ $type->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('type_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 写真（ファイル選択） --}}
                    <div class="flex flex-col items-start gap-2 pt-2">
                        <label class="block text-[#DA5019] font-bold" for="imageInput">写真</label>
                        {{-- 本物のボタンは隠す --}}
                        <input type="file" name="image_path" id="imageInput" onchange="previewImage(this)" class="hidden" accept="image/*">

                        <input type="hidden" name="current_image_status" id="currentImageStatus" value="{{ old('current_image_status', 'no_change') }}">
                        <input type="hidden" name="buffered_image_data" id="bufferedImageData" value="{{ old('buffered_image_data') }}">

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                            <label for="imageInput"
                                   class="cursor-pointer bg-[#DA5019] text-sm md:text-base text-white px-5 py-2.5 rounded-lg font-bold hover:bg-[#b84315] transition text-center shadow-sm shrink-0">
                                画像を選択
                            </label>
                            {{-- ファイル名を表示（既存画像があるかないかで表示を出し分け） --}}
                            <span id="fileNameDisplay" class="text-gray-500 text-sm italic break-all">
                                {{ $menu->image_path ? '既存の画像が選択されています。' : 'ファイルが選択されていません。' }}
                            </span>
                        </div>

                        {{-- バリデーションエラーを表示 --}}
                        <p id="jsImageError" class="text-red-500 text-sm mt-1" style="white-space: pre-line; text-align: left;"></p>
                        @error('image_path')
                            <p class="text-red-500 text-sm mt-1 laravel-image-error" style="white-space: pre-line; text-align: left;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 画像のプレビュー表示エリア --}}
                    <div class="w-full max-w-[400px] aspect-[16/10] relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 overflow-hidden">
                        {{-- 既存の画像パスを初期表示 --}}
                        <img src="{{ $imagePath }}" id="imagePreview" alt="メニュー画像プレビュー" class="w-full h-full object-contain {{ $menu->image_path ? '' : 'hidden' }}">

                        {{-- 画像がない時に表示するエリア --}}
                        <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-4 {{ $menu->image_path ? 'hidden' : '' }}">
                            <span class="text-gray-400 text-xs sm:text-sm text-center">画像が選択されていません。</span>
                        </div>

                        {{-- プレビュー画像削除ボタン（既存画像があるなら最初から出す） --}}
                        <button type="button" id="deleteImageBtn" onclick="clearImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition shadow {{ $menu->image_path ? '' : 'hidden' }}">✕</button>
                    </div>

                    {{-- 送信ボタン --}}
                    <div class="flex justify-center">
                        <button type="submit" class="w-full text-sm md:text-base md:w-auto bg-[#DA5019] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#b84315] transition shadow-md cursor-pointer text-center" id="save-menu-btn">
                            メニューを更新
                        </button>
                    </div>
                </form>

            </div>

            {{-- 💡 ナビゲーションも親ファイルと同じ位置に配置 --}}
            <x-sub-navigation current="menus" />
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/menu-create.js')
    @endpush
</x-app-layout>

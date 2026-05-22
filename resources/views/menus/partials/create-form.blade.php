<form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" id="menu-form" class="space-y-6 text-left">
    @csrf

    {{-- メニュー名 --}}
    <div class="flex flex-col items-start gap-2 mt-4">
        <label for="menu_name" class="text-[#DA5019] font-bold">メニュー名※</label>
        <input type="text" name="menu_name" value="{{ old('menu_name') }}" class="w-full md:w-[70%] border border-[#DA5019] focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:border-none rounded-md shadow-sm p-2">
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
                    <input type="radio" name="type_id" value="{{ $type->id }}"
                        {{ old('type_id') === $type->id ? 'checked' : '' }}
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
        <input type="file" name="image_path" id="imageInput" onchange="previewImage(this)" class="hidden">

        {{-- ボタンとファイル名の並び：スマホでは縦並びにして溢れを防ぎ、sm以上で横並びに --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
            {{-- 自作のボタン：スマホでもタップしやすいサイズ --}}
            <label for="imageInput" class="cursor-pointer bg-[#DA5019] text-sm md:text-base text-white px-5 py-2.5 rounded-lg font-bold hover:bg-[#b84315] transition text-center shadow-sm shrink-0">
                画像を選択
            </label>
            {{-- ファイル名を表示 --}}
            <span id="fileNameDisplay" class="text-gray-500 text-sm italic break-all">ファイルが選択されていません。</span>
        </div>

        {{-- バリデーションエラーを表示 --}}
        <p id="jsImageError" class="text-red-500 text-sm mt-1"></p>
        @error('image_path')
            <p class="text-red-500 text-sm mt-1 laravel-image-error" style="white-space: pre-line; text-align: left;">{{ $message }}</p>
        @enderror
    </div>

    {{-- 画像のプレビュー表示エリア --}}
    <div class="w-full max-w-[400px] aspect-[16/10] relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 overflow-hidden">
        {{-- 初期状態では、hiddenをつけて隠す --}}
        <img src="" id="imagePreview" alt="メニュー画像プレビュー" class="w-full h-full object-contain hidden">

        {{-- 画像がない時に表示するエリア --}}
        <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-4">
            <span class="text-gray-400 text-xs sm:text-sm text-center">画像が選択されていません。</span>
        </div>

        {{-- プレビュー画像削除ボタン --}}
        <button type="button" id="deleteImageBtn" onclick="clearImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition shadow hidden">✕</button>
    </div>

    {{-- 送信ボタン --}}
    <div class="flex justify-center">
        {{-- スマホでは押しやすいように横幅いっぱい（w-full）、PC（md）ではコンテンツに合わせる --}}
        <button type="submit" class="w-full text-sm md:text-base md:w-auto bg-[#DA5019] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#b84315] transition shadow-md cursor-pointer text-lg text-center" id="save-menu-btn">
            メニューを追加
        </button>
    </div>
</form>

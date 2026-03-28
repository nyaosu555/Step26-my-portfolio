<form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
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
    {{-- <div>
        <label>ジャンル※</label>
        <input type="radio" name="type_id" value="1" {{old('type_id') == 1 ? 'checkd' : ''}}>メイン
        <input type="radio" name="type_id" value="2" {{old('type_id') == 2 ? 'checkd' : ''}}>副菜A
        <input type="radio" name="type_id" value="3" {{old('type_id') == 3 ? 'checkd' : ''}}>副菜B
        @error('type_id')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div> --}}
    <div class="mb-6">
        <label class="block text-gray-700 font-bold mb-2">ジャンル※</label>
        <div class="flex flex-wrap gap-6">
            @foreach ($types as $type)
                <label for="type_id" class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="type_id" value="{{ $type->id }}"
                        {{ old('type_id') === $type->id ? 'checked' : '' }}
                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500"
                    >
                    <span class="ml-2 text-gray-700">{{ $type->name }}</span>
                </label>
            @endforeach
        </div>
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

    <button type="submit" class="mt-10">メニューを追加</button>
</form>

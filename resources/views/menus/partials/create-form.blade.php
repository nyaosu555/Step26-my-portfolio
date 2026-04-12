<form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data"  id="menu-form">
    @csrf
    {{-- メニュー名 --}}
    <div class="flex flex-col items-start gap-2">
        <label for="menu_name" class="text-[#DA5019] font-bold">メニュー名※</label>
        <input type="text" name="menu_name" value="{{ old('menu_name') }}" class="w-[70%] border border-[#DA5019] border-[#DA5019] focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:border-none rounded-md shadow-sm">
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
    <div class="flex flex-col items-start gap-2 my-6">
        <label class="block text-[#DA5019] font-bold mb-2">ジャンル※</label>
        <div class="flex flex-wrap gap-6">
            @foreach ($types as $type)
                <label for="type_{{ $type->id }}" class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="type_id" value="{{ $type->id }}"
                        {{ old('type_id') === $type->id ? 'checked' : '' }}
                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500"
                        id="type_{{ $type->id }}"
                    >
                    <span class="ml-2 text-[#DA5019]">{{ $type->name }}</span>
                </label>
            @endforeach
        </div>
        @error('type_id')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- 写真（ファイル選択） --}}
    <div class="flex flex-col items-start gap-2 my-6">
        <label class="block text-[#DA5019] font-bold mb-2" for="">写真</label>
        {{-- 本物のボタンは隠す --}}
        <input type="file" name="image" id="imageInput" onchange="previewImage(this)" class="hidden">
        <div class="flex items-center gap-4">
            {{-- 自作のボタン --}}
            <label for="imageInput" class="cursor-pointer bg-[#DA5019] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#b84315] transition">
                画像を選択
            </label>
            {{-- ファイル名を表示 --}}
            <span id="fileNameDisplay" class="text-gray-600 text-sm italic">ファイルが選択されていません。</span>
        </div>
    </div>
    {{-- 画像のプレビュー表示エリア --}}
    <div class="mt-2 w-[400px] h-[250px] relative border-dashed border rounded-lg relative">
        {{-- 初期状態では、hiddenをつけて隠す --}}
        <img src="" alt="" id="imagePreview" alt="メニュー画像プレビュー" class="w-full h-full border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 object-contain hidden">
        {{-- 画像がない時に表示するエリア --}}
        <div id="placeholder" class="text-gray-400 text-sm flex flex-col items-center absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]">
            <span id="placeholder" class="text-gray-400 text-xs text-center p-2">画像が選択されていません。</span>
        </div>
        {{-- プレビュー画像削除ボタン --}}
        <button type="button" id="deleteImageBtn" onclick="clearImage()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 hidden">✕</button>
    </div>

    <button type="submit" class="mt-10 cursor-pointer bg-[#DA5019] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#b84315] transition" id="save-menu-btn">メニューを追加</button>
</form>

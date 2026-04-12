<div class="space-y-4">
    @foreach ($menus as $menu)
        <div class="flex items-center bg-[#FDF2C4] p-4 rounded-2xl shadow-sm border border-orange-100 relative">
            {{-- 左：画像 --}}
            <div class="w-[130px] h-[86px] flex-shrink-0 bg-white rounded-xl overflow-hidden mr-4">
                @if($menu->image_path)
                    <img src="{{ asset('storage/' . $menu->image_path) }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('images/no_image.png') }}" class="w-full h-full object-cover">
                @endif
            </div>

            {{-- 中央：テキスト --}}
            <div class="flex-grow text-left">
                <h3 class="text-orange-800 font-bold text-[18px] leading-[1.7]">{{ $menu->name }}</h3>
                <p class="text-orange-600 text-sm">{{ $menu->type->name ?? '未分類' }}</p>
            </div>

            {{-- 右：削除ボタン --}}
            {{-- <form action="{{ route('menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')"> --}}
            <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="delete-menu-form">
                @csrf
                @method('DELETE')
                {{-- <button type="submit" class="p-2 text-gray-800 hover:text-red-500 transition"> --}}
                {{-- submitだと勝手に送信されてしまうのでbuttonに変更する --}}
                <button type="button" class="p-2 text-gray-800 hover:text-red-500 transition delete-individual-btn" data-name="{{ $menu->name }}">
                    {{-- ゴミ箱アイコン（SVG） --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    @endforeach
</div>

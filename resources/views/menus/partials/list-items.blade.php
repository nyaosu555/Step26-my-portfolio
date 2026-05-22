<div class="flex flex-wrap -mx-3 justify-start text-left">
    @foreach ($menus as $menu)
        <div class="w-full sm:w-1/2 xl:w-1/3 px-3 pb-6 flex">
            <div class="flex flex-col flex-grow w-full bg-[#FDF2C4] rounded-2xl shadow-sm border border-orange-100 overflow-hidden relative group hover:shadow-md transition duration-200">

                <div class="w-full aspect-[16/10] bg-white overflow-hidden relative border-b border-orange-900/5">
                    @if($menu->image_path)
                        <img src="{{ asset('storage/' . $menu->image_path) }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                    @else
                        <img src="{{ asset('images/no_image.png') }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-grow justify-between gap-3">

                    <div class="space-y-1.5">
                        <h3 class="text-orange-800 font-bold text-[18px] leading-[1.4] break-all">
                            {{ $menu->name }}
                        </h3>

                        {{-- ジャンルに応じた動的バッジカラー（ピンク・オレンジ・黄色） --}}
                        @if($menu->type_id == \App\Enums\MenuType::Main->value)
                            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded bg-red-50 text-red-800">
                                {{ $menu->type->name ?? 'メイン' }}
                            </span>
                        @elseif($menu->type_id == \App\Enums\MenuType::SideA->value)
                            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded bg-orange-100 text-orange-800">
                                {{ $menu->type->name ?? '副菜A' }}
                            </span>
                        @elseif($menu->type_id == \App\Enums\MenuType::SideB->value)
                            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded bg-yellow-100 text-yellow-800">
                                {{ $menu->type->name ?? '副菜B' }}
                            </span>
                        @else
                            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                未分類
                            </span>
                        @endif

                        {{-- 管理者の場合のみ投稿者名を表示 --}}
                        @if (auth()->user()->role === 'admin')
                            <p class="mt-1 text-gray-500 text-xs">投稿者： {{ $menu->user->name }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end items-center border-t border-orange-900/10 pt-2 mt-auto">
                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="delete-menu-form">
                            @csrf
                            @method('DELETE')

                            @if ($menu->user_id === auth()->id() || auth()->user()->role === 'admin')
                                <button type="button" class="p-2 text-gray-800 hover:text-red-500 hover:bg-red-50 rounded-lg transition delete-individual-btn cursor-pointer" data-name="{{ $menu->name }}" title="削除">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                <button disabled type="button" class="p-2 text-gray-400 opacity-40 delete-individual-btn cursor-not-allowed" title="登録者本人か管理者権限が必要です">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>

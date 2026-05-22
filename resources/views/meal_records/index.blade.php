<x-app-layout>
    <div class="relative z-10 w-[95%] lg:w-[80%] mx-auto bg-[#fee5a5] rounded-[2em] shadow-2xl p-4 sm:p-8 md:p-10 text-center mb-16 md:mb-0">

        <x-flash-message
            :message="session('message')"
            :type="session('type')" />

        <div class="mx-auto py-6 sm:py-12 flex flex-col gap-6 sm:gap-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                献立の履歴
            </h2>

            {{-- 削除ボタン：スマホでは押しやすいように幅を広げ、中央に配置（PCでは左寄せ） --}}
            <div class="flex justify-center md:justify-start">
                <button id="select-delete-btn" class="w-full max-w-[200px] bg-red-500 hover:bg-red-600 text-white py-2.5 rounded shadow text-sm transition font-bold">
                    選択した項目を削除
                </button>
            </div>

            <div class="w-full mx-auto">
                <div class="block md:hidden space-y-4">
                    @foreach ($mealRecords as $record)
                        <div class="bg-white rounded-2xl shadow border border-black overflow-hidden text-left">
                            {{-- カードヘッダー：日付と削除チェック（タップ領域を広く確保） --}}
                            <div class="bg-[#FFB908] px-4 py-3 flex justify-between items-center border-b border-black">
                                <span class="font-bold text-gray-900 text-sm flex gap-2">
                                    <svg width="18" height="18" viewBox="0 0 72 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M64 8H60V0H52V8H20V0H12V8H8C3.56 8 0.04 11.6 0.04 16L0 72C0 76.4 3.56 80 8 80H64C68.4 80 72 76.4 72 72V16C72 11.6 68.4 8 64 8ZM64 72H8V32H64V72ZM24 48H16V40H24V48ZM40 48H32V40H40V48ZM56 48H48V40H56V48ZM24 64H16V56H24V64ZM40 64H32V56H40V64ZM56 64H48V56H56V64Z" fill="#991B1B"/>
                                    </svg>
                                    {{ str_replace('-', '/', $record->date) }}
                                </span>
                                <label class="flex items-center space-x-2 bg-white/80 px-2.5 py-1 rounded-lg border border-black/20 cursor-pointer select-none-none">
                                    <input type="checkbox" name="record_ids[]" value="{{$record->id}}" class="record-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500 size-4">
                                    <span class="text-xs font-bold text-red-600">削除</span>
                                </label>
                            </div>
                            {{-- カードボディ：料理の中身（バッジ風のラベルで視認性アップ） --}}
                            <div class="p-4 space-y-2.5 text-sm font-medium">
                                <div class="flex items-start">
                                    <span class="w-16 text-xs bg-red-50 text-red-800 px-1.5 py-0.5 rounded font-bold text-center mr-3 shrink-0">メイン</span>
                                    <span class="text-gray-800">{{ $record->mainDish?->menu?->name ?? '（削除済み）' }}</span>
                                </div>
                                <div class="flex items-start border-t border-gray-100 pt-2">
                                    <span class="w-16 text-xs bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded font-bold text-center mr-3 shrink-0">副菜A</span>
                                    <span class="text-gray-700">{{ $record->sideDishA?->menu?->name ?? '（削除済み）' }}</span>
                                </div>
                                <div class="flex items-start border-t border-gray-100 pt-2">
                                    <span class="w-16 text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded font-bold text-center mr-3 shrink-0">副菜B</span>
                                    <span class="text-gray-700">{{ $record->sideDishB?->menu?->name ?? '（削除済み）' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hidden md:block w-full overflow-x-auto">
                    <table class="min-w-full table-fixed border-collapse border border-black divide-y divide-black bg-white">
                        <thead class="bg-[#FFB908]">
                            <tr>
                                <th class="w-[10%] px-3 py-3 text-center text-xs font-bold uppercase border-r border-black">削除</th>
                                <th class="w-[15%] px-3 py-3 text-center text-xs font-bold uppercase border-r border-black">日付</th>
                                <th class="w-[25%] px-4 py-3 text-left text-xs font-bold uppercase border-r border-black">メイン</th>
                                <th class="w-[25%] px-4 py-3 text-left text-xs font-bold uppercase border-r border-black">副菜A</th>
                                <th class="w-[25%] px-4 py-3 text-left text-xs font-bold uppercase border-r border-black">副菜B</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black">
                            @foreach ($mealRecords as $record)
                                <tr class="hover:bg-amber-50/50 transition duration-150">
                                    <td class="px-3 py-4 text-center border-r border-black">
                                        <input type="checkbox" name="record_ids[]" value="{{$record->id}}" class="record-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                    </td>
                                    <td class="px-3 py-4 text-center whitespace-nowrap text-sm font-medium text-gray-900 border-r border-black">
                                        {{ str_replace('-', '/', $record->date) }}
                                    </td>
                                    <td class="px-4 py-4 text-left text-sm font-medium text-gray-700 border-r border-black break-words">
                                        {{ $record->mainDish?->menu?->name ?? '（削除済み）' }}
                                    </td>
                                    <td class="px-4 py-4 text-left text-sm font-medium text-gray-700 border-r border-black break-words">
                                        {{ $record->sideDishA?->menu?->name ?? '（削除済み）' }}
                                    </td>
                                    <td class="px-4 py-4 text-left text-sm font-medium text-gray-700 break-words">
                                        {{ $record->sideDishB?->menu?->name ?? '（削除済み）' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ページネーション --}}
            <div class="mt-6 px-2">
                {{ $mealRecords->links() }}
            </div>

            {{-- サブナビゲーション（先ほど修正したコンポーネント） --}}
            <x-sub-navigation current="meal-records" />
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/meal-record.js'])
    @endpush
</x-app-layout>

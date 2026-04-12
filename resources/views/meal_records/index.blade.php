<x-app-layout>
    <div class="relative z-10 w-[80%] mx-auto bg-[#fee5a5] rounded-[2em] shadow-2xl p-8 md:p-10 text-center">
        <x-flash-message
            :message="session('message')"
            :type="session('type')" />
        <div class="mx-auto py-12 flex flex-col gap-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                献立の履歴
            </h2>
            <button id="select-delete-btn" class="w-[200px] bg-red-500 hover:bg-red-600 text-white py-2 rounded shadow text-sm transition">
                選択した項目を削除
            </button>
            <div class="w-full mx-auto">
                <table class="min-w-full border-collapse border border-black divide-y divide-black ">
                    <thead class="bg-[#FFB908]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-black">削除</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-black">日付</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-black">メイン</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-black">副菜A</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-black">副菜B</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-black">
                        @foreach ($mealRecords as $record)
                            @php
                                // 1. 各タイプを事前に抽出（リレーション名は mealRecordItems である前提です）
                                $main = $record->mealRecodItems->where('type_id', 1)->first();
                                $sideA = $record->mealRecodItems->where('type_id', 2)->first();
                                $sideB = $record->mealRecodItems->where('type_id', 3)->first();
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-center border-r border-black">
                                    <input type="checkbox" name="record_ids[]" value="{{$record->id}}" class="record-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                </td>
                                <td class="px-6 py-4 text-left whitespace-nowrap text-sm font-medium text-gray-900 border-r border-black">
                                    {{str_replace('-', '/', $record->date)}}
                                </td>
                                <td class="px-6 py-4 text-left whitespace-nowrap text-sm font-medium text-gray-700 border-r border-black">
                                    {{$main->menu->name ?? '_'}}
                                </td>
                                <td class="px-6 py-4 text-left whitespace-nowrap text-sm font-medium text-gray-700 border-r border-black">
                                    {{$sideA->menu->name ?? '_'}}
                                </td>
                                <td class="px-6 py-4 text-left whitespace-nowrap text-sm font-medium text-gray-700 border-r border-black">
                                    {{$sideB->menu->name ?? '_'}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        {{-- ナビゲーション --}}
                <x-sub-navigation current="meal-records" />
        </div>
    </div>
    @push('scripts')
        @vite(['resources/js/meal-record.js'])
    @endpush
</x-app-layout>

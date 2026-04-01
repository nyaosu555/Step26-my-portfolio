<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            献立の履歴
        </h2>
        <button id="select-delete-btn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow text-sm transition">
            選択した項目を削除
        </button>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-yellow-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">削除</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">日付</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">メイン</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">副菜A</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">副菜B</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($mealRecords as $record)
                        @php
                            // 1. 各タイプを事前に抽出（リレーション名は mealRecordItems である前提です）
                            $main = $record->mealRecodItems->where('type_id', 1)->first();
                            $sideA = $record->mealRecodItems->where('type_id', 2)->first();
                            $sideB = $record->mealRecodItems->where('type_id', 3)->first();
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="record_ids[]" value="{{$record->id}}" class="record-checkbox rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{str_replace('-', '/', $record->date)}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{$main->menu->name ?? '_'}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{$sideA->menu->name ?? '_'}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{$sideB->menu->name ?? '_'}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/meal-record.js'])
    @endpush
</x-app-layout>

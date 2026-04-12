@props(['current' => ''])

<div {{ $attributes->merge(['class' => 'flex justify-end space-x-6 text-[#d97706] font-bold tetx-sm md:text-base']) }}>
    {{-- スロット画面以外なら「スロットに戻る」を表示 --}}
    @if ($current !== 'slot')
        <a href="{{ route('index')}}" class="flex items-center hover:opacity-70"><span class="mr-1">🎰</span>スロットに戻る</a>
    @endif

    {{-- スロット画面の時だけ「保存」を表示 --}}
    @if ($current === 'slot')
        <a href="#" id="save-button" class="flex items-center hover:opacity-70"><span class="mr-1">⬇️</span>献立を保存する</a>
    @endif

    {{-- 履歴画面以外なら「履歴一覧」を表示 --}}
    @if ($current !== 'meal-records')
        <a href="/meal-records" class="flex items-center hover:opacity-70"><span class="mr-1">🕐</span>保存した献立一覧</a>
    @endif

    {{-- メニュー管理（権限チェック付き） --}}
    @can('admin')
        <a href="{{ route('menus.index') }}" class="flex items-center hover:opacity-70"><span class="mr-1">⬆️</span>メニュー管理</a>
    @else
        <button disabled class="flex items-center opacity-30 cursor-not-allowed" title="管理者権限が必要です"><span class="mr-1">⬆️</span>メニュー管理（制限中）</a>
    @endcan
</div>

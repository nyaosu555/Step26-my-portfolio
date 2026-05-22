{{-- @props(['message', 'type' => 'success']) --}}

{{-- @if($type === 'success')
    @php
        $classes = 'p-4 m-2 rounded bg-green-100 fixed top-0 left-0 right-0 z-50 rounded-xl border-2 border-green-700'
    @endphp
@elseif($type === 'danger')
    @php
        $classes = 'p-4 m-2 rounded bg-red-100 fixed top-0 left-0 right-0 z-50 rounded-xl border-2 border-red-700'
    @endphp
@endif

@if($message)
    <div class="p-4 m-2 rounded {{$classes}}
            transform -translate-y-full opacity-0
            transition-all duration-500 ease-out" id="flash-message">
        {{ $message }}
    </div>
@endif --}}

@props(['message' => session('message') ?? '', 'type' => session('type') ?? 'success'])

@php
    $typeClasses = ($type === 'danger')
        ? 'bg-red-100 border-red-700 text-red-800'
        : 'bg-green-100 border-green-700 text-green-800';
@endphp

{{-- IDを固定し、初期状態は常に隠す (-translate-y-full opacity-0) --}}
<div id="flash-message"
     class="p-4 m-2 fixed top-0 left-0 right-0 z-[110] rounded-xl border-2 font-bold text-left shadow-lg transform -translate-y-full opacity-0 transition-all duration-500 ease-out flex items-center gap-3 {{ $typeClasses }}">

    {{-- JSで書き換えるアイコンの器 --}}
    <span id="flash-icon" class="flex-shrink-0">
        @if (str_contains($typeClasses, 'green'))
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        @endif
    </span>

    {{-- JSで書き換えるテキストの器 --}}
    <span id="flash-text" class="text-sm md:text-base">{{ $message }}</span>
</div>

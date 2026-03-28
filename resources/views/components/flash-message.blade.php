@props(['message', 'type' => 'success'])

@if($type === 'success')
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
@endif

@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#DA5019] focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:border-none rounded-md shadow-sm']) }}>

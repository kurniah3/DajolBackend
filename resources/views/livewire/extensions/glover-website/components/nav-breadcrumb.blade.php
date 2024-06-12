<div>
    {{-- large screen --}}
    <div class="hidden md:flex items-center mb-6">
        @foreach ($links as $link)
            <a href="{{ $link['url'] }}" class="text-gray-500 hover:text-gray-700">
                {{ $link['title'] }}
            </a>
            {{-- hide is its last loop --}}
            @if (!$loop->last)
                <span class="mx-2 text-gray-500">
                    <x-heroicon-o-chevron-right class="w-4 h-4 hidden ltr:block" />
                    <x-heroicon-o-chevron-left class="w-4 h-4 hidden rtl:block" />
                </span>
            @endif
        @endforeach
    </div>

    {{-- small screen --}}
    <div class="block md:hidden items-center mb-6 space-y-1 w-full">
        @foreach ($links as $link)
            <div class="flex items-center">
                <span class="text-gray-500">
                    <x-heroicon-o-chevron-right class="w-4 h-4 hidden ltr:block" />
                    <x-heroicon-o-chevron-left class="w-4 h-4 hidden rtl:block" />
                </span>

                <a href="{{ $link['url'] }}" class="text-gray-500 hover:text-gray-700 break-words">
                    {{ $link['title'] }}
                </a>
            </div>
        @endforeach
    </div>
</div>

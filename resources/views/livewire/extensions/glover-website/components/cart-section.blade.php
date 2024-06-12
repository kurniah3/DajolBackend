<div>
    <a href="{{ route('glover-website.cart') }}" class=" 'ml-4 text-sm font-bold flex'">
        <div class="relative">
            <x-heroicon-s-shopping-cart class="h-6 w-6 {{ $styles ?? 'text-theme' }}" />
            <div
                class="absolute -top-2 -right-2 inline-flex items-center justify-center h-5 w-5 bg-red-600 text-white rounded-full text-xs p-1">
                {{ $totalItem ?? 0 }}
            </div>
        </div>
    </a>
</div>

<div>
    @if ($showEmpty || $products->count() > 0)
        <div class="mt-16">
            <div class='flex items-center my-4'>
                <div class="{{ isRTL() ? 'ml-auto' : 'mr-auto' }}">
                    <p class="text-start text-2xl font-semibold"> {{ $title ?? __('Popular Nearby Products') }}</p>
                    <p class="text-start text-sm font-light">
                        {{ $subtitle ?? __('Best selling products around selected location') }}</p>
                </div>
                {{-- view all button --}}
                @if ($products->hasMorePages())
                    @php
                        $link = route('glover-website.search', [
                            'vendor_type_id' => $vendor_type_id,
                            'sort' => 'popular-nearby',
                            'type' => 'product',
                        ]);
                    @endphp
                    <a href="{{ $link ?? '' }}" class="">
                        <div
                            class="border border-gray-500 rounded-full px-4 py-2 flex items-center text-xs text-gray-500 hover:text-gray-700 space-x-2">
                            <span>{{ __('View All') }}</span>
                            @if (isRTL())
                                <x-heroicon-o-arrow-left class="inline-block w-4 h-4" />
                            @else
                                <x-heroicon-o-arrow-right class="inline-block w-4 h-4" />
                            @endif
                        </div>
                    </a>
                @endif
            </div>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 md:space-y-0 space-y-4 justify-items-stretch">
                @foreach ($products as $product)
                    @include('livewire.extensions.glover-website.components.list.product-card', [
                        'product' => $product,
                    ])
                @endforeach
            </div>
        </div>
    @endif

</div>
